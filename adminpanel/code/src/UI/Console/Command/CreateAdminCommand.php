<?php

declare(strict_types=1);

namespace App\UI\Console\Command;

use App\Application\Auth\Commands\Command\SignUpCommand;
use App\Application\Auth\Request\SignUpRequest;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus, string $name = null)
    {
        parent::__construct($name);
        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create admin')
            ->setHelp('Create admin')
            ->addArgument('login', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $request = new SignUpRequest(
            (string) $input->getArgument('login'),
            (string) $input->getArgument('password'),
            'Super',
            'Admin'
        );

        try {
            $this->commandBus->handle(new SignUpCommand($request));
            $output->writeln('admin is created');
        } catch (Throwable $exception) {
            $output->writeln($exception->getMessage());
        }

        return 0;
    }
}
