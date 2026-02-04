<?php

declare(strict_types=1);

namespace App\Export;

final readonly class ExportClientConfig
{
    public function __construct(
        public string $baseUrl,
        public string $apiKey,
        public int $timeoutSeconds = 10,
    ) {
    }

    public static function fromEnv(): self
    {
        return new self(
            baseUrl: getenv('SIS_EXPORT_BASE_URL') ?: '',
            apiKey: getenv('SIS_EXPORT_API_KEY') ?: '',
            timeoutSeconds: (int) (getenv('SIS_EXPORT_TIMEOUT') ?: '10'),
        );
    }
}
