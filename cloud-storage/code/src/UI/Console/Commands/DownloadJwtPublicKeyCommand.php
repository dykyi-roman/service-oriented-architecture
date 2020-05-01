<?php

declare(strict_types=1);

namespace App\UI\Console\Commands;

use App\Domain\Auth\Exception\AuthException;
use App\Domain\Auth\Service\Guard;
use Illuminate\Console\Command;

class DownloadJwtPublicKeyCommand extends Command
{
    protected $signature = 'app:download-jwt-public-key {--iteration_sleep=}';

    protected $description = 'Download JWT public key';

    private Guard $guard;

    public function __construct(Guard $guard)
    {
        parent::__construct();

        $this->guard = $guard;
    }

    public function handle()
    {
        while (true) {
            try {
                $result = $this->guard->downloadPublicKey(env('JWT_PUBLIC_KEY'));
                $this->info(sprintf('JWT key is updated status %s', $result ? 'success' : 'failed'));
            } catch (AuthException $exception) {
                $this->error(sprintf('Error: %s', $exception->getMessage()));
            }

            sleep((int)$this->option('iteration_sleep'));
        }
    }
}
