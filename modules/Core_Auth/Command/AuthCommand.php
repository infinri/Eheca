<?php

namespace Modules\Core_Auth\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'auth:manage',
    description: 'Manage authentication related tasks',
    hidden: false
)]
class AuthCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp('This command helps manage authentication related tasks')
            ->addOption('list-users', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'List all users');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('list-users')) {
            // Add logic to list users
            $output->writeln('List of users will be displayed here');
            return Command::SUCCESS;
        }

        $output->writeln('Authentication management command');
        $output->writeln('Use --help to see available options');
        
        return Command::SUCCESS;
    }
}
