<?php

declare(strict_types=1);

namespace App\UI\Terminal;

use App\Application\Sender\SenderConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SenderCommand extends Command
{
    protected static $defaultName = 'message:sender:run';

    private SenderConsumer $consumer;

    public function __construct(SenderConsumer $consumer, $name = null)
    {
        $this->consumer = $consumer;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a new message sender')
            ->setHelp('This command allows you to create a new message sender consumer')
            ->addArgument('queue', InputArgument::REQUIRED, 'Queue name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(['| Queue created |', '================', '',]);
        $this->consumer->execute($input->getArgument('queue'));

        return 0;
    }
}
