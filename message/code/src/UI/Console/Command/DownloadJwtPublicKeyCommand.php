<?php

declare(strict_types=1);

namespace App\UI\Console\Command;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Service\Guard;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DownloadJwtPublicKeyCommand extends Command
{
    private const DEFAULT_ITERATION_SLEEP = 60 * 60 * 12;

    protected static $defaultName = 'app:download-jwt-public-key';

    private Guard $guard;
    private ParameterBagInterface $bag;

    public function __construct(Guard $guard, ParameterBagInterface $bag, string $name = null)
    {
        parent::__construct($name);
        $this->guard = $guard;
        $this->bag = $bag;
    }

    protected function configure()
    {
        $this
            ->setDescription('Download JWT public key')
            ->setHelp('Download JWT public key')
            ->addOption(
                'iteration_sleep',
                null,
                InputOption::VALUE_OPTIONAL,
                'Seconds between iterations',
                self::DEFAULT_ITERATION_SLEEP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while (true) {
            try {
                $result = $this->guard->downloadPublicKey($this->bag->get('JWT_PUBLIC_KEY'));
                $output->write(sprintf('JWT key is updated status %s', $result ? 'success' : 'failed'));
            } catch (AuthException $exception) {
                $output->write(sprintf('Error: %s', $exception->getMessage()));
            }

            sleep((int)$input->getOption('iteration_sleep'));
        }

        return 0;
    }
}
