<?php

declare(strict_types=1);

namespace App\Notification;

final class EmailNotificationSender implements NotificationSenderInterface
{
    public function __construct(
        private MailerConfig $config,
    ) {
    }

    public function send(string $recipientId, string $subject, string $body): void
    {
        // In a real app would use the config to send via SMTP; here we only hold config
        // so that construction is testable and we don't read env inside the class.
        if ($this->config->host === '') {
            throw new \RuntimeException('Mailer not configured');
        }
        // Stub: would send email using $this->config
    }
}
