<?php

declare(strict_types=1);

namespace App\Export;

use Psr\Log\LoggerInterface;

/**
 * Resolves which export client to use at runtime (e.g. from env).
 * Config is built here and passed into implementations.
 */
final class AttendanceExportClientFactory
{
    public function __construct(
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function create(): AttendanceExportClientInterface
    {
        $baseUrl = getenv('EXPORT_BASE_URL');
        if ($baseUrl !== false && $baseUrl !== '') {
            return new RealExportClient(ExportClientConfig::fromEnv());
        }
        if ($this->logger !== null) {
            return new LogOnlyExportClient($this->logger);
        }
        return new NullExportClient();
    }
}
