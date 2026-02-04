<?php

declare(strict_types=1);

namespace App\Notification;

use Psr\Log\LoggerInterface;

/**
 * Resolves which notification sender to use at runtime (e.g. from env).
 * Config is built here and passed into implementations; they do not read env.
 */
final class NotificationSenderFactory
{
    public function __construct(
        private ?LoggerInterface $logger = null,
    ) {
    }

    public function create(): NotificationSenderInterface
    {
        $dsn = getenv('MAIL_DSN');
        if ($dsn !== false && $dsn !== '') {
            return new EmailNotificationSender(MailerConfig::fromEnv());
        }
        if ($this->logger !== null) {
            return new LogNotificationSender($this->logger);
        }
        return new NoOpNotificationSender();
    }
}
