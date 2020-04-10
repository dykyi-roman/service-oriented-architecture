<?php

declare(strict_types=1);

namespace App\Domain\Service;

final class AvailableAdaptersFinder
{
    public function supported(): array
    {
        $adapters = [];
        $files = $this->getFileListByPath(__DIR__ . '/../../Infrastructure/Adapters/');
        array_walk($files, static function ($item) use (&$adapters) {
            $adapters[] = str_replace('Adapter.php', '', $item);
        });

        return $adapters;
    }

    private function getFileListByPath(string $path): array
    {
        $files = [];
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry !== '.' && $entry !== '..') {
                    $files[] = $entry;
                }
            }
            closedir($handle);
        }

        return $files;
    }
}

