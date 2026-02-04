<?php

declare(strict_types=1);

namespace App\Notification;

final readonly class MailerConfig
{
    public function __construct(
        public string $host,
        public int $port,
        public string $fromAddress,
        public string $fromName = 'Attendance Service',
    ) {
    }

    public static function fromEnv(): self
    {
        return new self(
            host: getenv('MAIL_HOST') ?: 'localhost',
            port: (int) (getenv('MAIL_PORT') ?: '1025'),
            fromAddress: getenv('MAIL_FROM_ADDRESS') ?: 'noreply@localhost',
            fromName: getenv('MAIL_FROM_NAME') ?: 'Attendance Service',
        );
    }
}
