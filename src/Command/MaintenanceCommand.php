<?php

namespace Eheca\Command;

use Eheca\Controller\MaintenanceController;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:maintenance',
    description: 'Enable or disable maintenance mode'
)]
class MaintenanceCommand extends Command
{
    public function __construct(
        private readonly MaintenanceController $maintenanceController,
        private readonly string $projectDir
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('on', null, InputOption::VALUE_NONE, 'Enable maintenance mode')
            ->addOption('off', null, InputOption::VALUE_NONE, 'Disable maintenance mode')
            ->addOption('status', null, InputOption::VALUE_NONE, 'Check maintenance mode status')
            ->addOption('message', 'm', InputOption::VALUE_OPTIONAL, 'Custom maintenance message', 'The site is currently under maintenance. Please check back later.')
            ->addOption('allow', 'a', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Allow specific IPs (comma-separated)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filesystem = new Filesystem();
        $lockFile = $this->projectDir . '/var/maintenance.lock';

        $isOn = $input->getOption('on');
        $isOff = $input->getOption('off');
        $status = $input->getOption('status');

        if ($status || (!$isOn && !$isOff)) {
            return $this->displayStatus($io, $lockFile, $filesystem);
        }

        if ($isOn) {
            return $this->enableMaintenance($input, $io, $lockFile);
        }

        if ($isOff) {
            return $this->disableMaintenance($io, $lockFile, $filesystem);
        }

        return Command::SUCCESS;
    }

    private function displayStatus(SymfonyStyle $io, string $lockFile, Filesystem $filesystem): int
    {
        if ($filesystem->exists($lockFile)) {
            $data = json_decode(file_get_contents($lockFile), true);
            $io->warning('Maintenance mode is ENABLED');
            $io->writeln(sprintf('Enabled at: %s', $data['enabled_at']));
            $io->writeln(sprintf('Enabled by: %s', $data['enabled_by']));
            $io->writeln(sprintf('Message: %s', $data['message']));
            
            if (!empty($data['allowed_ips'])) {
                $io->writeln('Allowed IPs:');
                foreach ($data['allowed_ips'] as $ip) {
                    $io->writeln(sprintf('  - %s', $ip));
                }
            }
            
            return Command::SUCCESS;
        }

        $io->success('Maintenance mode is DISABLED');
        return Command::SUCCESS;
    }

    private function enableMaintenance(InputInterface $input, SymfonyStyle $io, string $lockFile): int
    {
        $message = $input->getOption('message');
        $allowedIps = $input->getOption('allow');

        $data = [
            'enabled_at' => (new \DateTimeImmutable())->format(\DateTime::ATOM),
            'enabled_by' => 'console',
            'message' => $message,
            'allowed_ips' => $allowedIps,
        ];

        file_put_contents($lockFile, json_encode($data, JSON_PRETTY_PRINT));
        
        $io->success('Maintenance mode has been ENABLED');
        $io->writeln(sprintf('Message: %s', $message));
        
        if (!empty($allowedIps)) {
            $io->writeln('Allowed IPs:');
            foreach ($allowedIps as $ip) {
                $io->writeln(sprintf('  - %s', $ip));
            }
        }
        
        return Command::SUCCESS;
    }

    private function disableMaintenance(SymfonyStyle $io, string $lockFile, Filesystem $filesystem): int
    {
        if ($filesystem->exists($lockFile)) {
            $filesystem->remove($lockFile);
            $io->success('Maintenance mode has been DISABLED');
            return Command::SUCCESS;
        }

        $io->note('Maintenance mode was not enabled');
        return Command::SUCCESS;
    }
}
