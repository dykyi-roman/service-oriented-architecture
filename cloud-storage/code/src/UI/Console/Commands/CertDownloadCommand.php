<?php

declare(strict_types=1);

namespace App\UI\Console\Commands;

use App\Application\Common\Service\ExceptionLogger;
use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Service\CertReceiver;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class CertDownloadCommand extends Command
{
    protected $signature = 'app:cert-download {--iteration_sleep=}';
    protected $description = 'Download JWT public key';

    private CertReceiver $certReceiver;
    private LoggerInterface $logger;

    public function __construct(CertReceiver $certReceiver, LoggerInterface $logger)
    {
        parent::__construct();

        $this->certReceiver = $certReceiver;
        $this->logger = $logger;
    }

    public function handle()
    {
        while (true) {
            try {
                $result = $this->certReceiver->downloadPublicKey(env('JWT_PUBLIC_KEY'));
                $this->info(sprintf('JWT key is updated status %s', $result ? 'success' : 'failed'));
            } catch (AuthException $exception) {
                $this->logger->error(...ExceptionLogger::log(__METHOD__, $exception->getMessage()));
            }

            sleep((int)$this->option('iteration_sleep'));
        }
    }
}
