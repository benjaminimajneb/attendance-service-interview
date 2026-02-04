<?php

declare(strict_types=1);

namespace App\Notification;

use Psr\Log\LoggerInterface;

final class LogNotificationSender implements NotificationSenderInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function send(string $recipientId, string $subject, string $body): void
    {
        $this->logger->info('Notification (log only)', [
            'recipient' => $recipientId,
            'subject' => $subject,
        ]);
    }
}
