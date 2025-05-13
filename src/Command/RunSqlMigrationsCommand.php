<?php
namespace App\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunSqlMigrationsCommand extends Command
{
    protected static $defaultName = 'app:migrate:sql';
    private Connection $conn;
    private string $logFile;

    public function __construct(Connection $conn)
    {
        parent::__construct();
        $this->conn = $conn;
        $this->logFile = __DIR__ . '/../../../var/log/sql_migrations.log';
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Runs raw SQL migrations from the migrations/ directory, applying each migration only once, with hashing and validation.')
            ->addOption('dry-run', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Show what would be run, but do not execute.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrationDir = __DIR__ . '/../../../migrations';
        $this->ensureMigrationsTable();
        $applied = $this->getAppliedMigrations(); // [name => hash]
        $files = glob($migrationDir . '/*.sql');
        sort($files);
        $ran = 0;
        $dryRun = $input->getOption('dry-run');
        $logLines = [];
        foreach ($files as $file) {
            $name = basename($file);
            $sql = file_get_contents($file);
            $hash = hash('sha256', $sql);
            // 1. Validate SQL file
            if (empty(trim($sql))) {
                $output->writeln("<error>Migration is empty:</error> $name");
                $logLines[] = "ERROR: $name is empty.";
                continue;
            }
            if (substr(trim($sql), -1) !== ';') {
                $output->writeln("<error>Migration does not end with semicolon:</error> $name");
                $logLines[] = "ERROR: $name missing semicolon.";
                continue;
            }
            // 2. Check hash for drift
            if (isset($applied[$name])) {
                if ($applied[$name] !== $hash) {
                    $output->writeln("<error>Migration drift detected:</error> $name (hash mismatch)");
                    $logLines[] = "ERROR: Drift $name (hash mismatch)";
                } else {
                    $output->writeln("<comment>Already applied:</comment> $name");
                    $logLines[] = "SKIP: $name already applied.";
                }
                continue;
            }
            // 3. Dry run mode
            if ($dryRun) {
                $output->writeln("<info>Would apply migration:</info> $name");
                $logLines[] = "DRYRUN: $name";
                continue;
            }
            // 4. Run migration
            $this->conn->beginTransaction();
            try {
                $this->conn->executeStatement($sql);
                $this->conn->insert('migrations', [
                    'migration' => $name,
                    'hash' => $hash,
                    'applied_at' => date('Y-m-d H:i:s'),
                ]);
                $this->conn->commit();
                $output->writeln("<info>Applied migration:</info> $name");
                $logLines[] = "APPLIED: $name";
                $ran++;
            } catch (\Throwable $e) {
                $this->conn->rollBack();
                $output->writeln("<error>Failed migration:</error> $name");
                $output->writeln($e->getMessage());
                $logLines[] = "FAILED: $name - " . $e->getMessage();
                file_put_contents($this->logFile, implode("\n", $logLines) . "\n", FILE_APPEND);
                return Command::FAILURE;
            }
        }
        $output->writeln("<info>Done.</info> $ran migrations applied.");
        file_put_contents($this->logFile, implode("\n", $logLines) . "\n", FILE_APPEND);
        return Command::SUCCESS;
    }

    private function ensureMigrationsTable(): void
    {
        $schema = $this->conn->createSchemaManager();
        if (!$schema->tablesExist(['migrations'])) {
            $this->conn->executeStatement(
                'CREATE TABLE migrations (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    migration VARCHAR(255) NOT NULL UNIQUE,
                    hash CHAR(64) NOT NULL,
                    applied_at DATETIME NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;'
            );
        } else {
            // Add hash column if missing (for legacy migrations table)
            $columns = $schema->listTableColumns('migrations');
            if (!isset($columns['hash'])) {
                $this->conn->executeStatement('ALTER TABLE migrations ADD hash CHAR(64) NOT NULL DEFAULT ""');
            }
        }
    }

    private function getAppliedMigrations(): array
    {
        $schema = $this->conn->createSchemaManager();
        if (!$schema->tablesExist(['migrations'])) {
            return [];
        }
        $rows = $this->conn->fetchAllAssociative('SELECT migration, hash FROM migrations');
        $result = [];
        foreach ($rows as $row) {
            $result[$row['migration']] = $row['hash'];
        }
        return $result;
    }
}

