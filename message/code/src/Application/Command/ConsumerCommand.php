<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Application\Consumer\MessageConsumer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ConsumerCommand extends Command
{
    protected static $defaultName = 'app:consumer:start';

    private MessageConsumer $consumer;

    public function __construct(MessageConsumer $consumer, $name = null)
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
