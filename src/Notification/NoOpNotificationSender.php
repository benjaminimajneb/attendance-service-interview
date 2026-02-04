<?php

declare(strict_types=1);

namespace App\Notification;

final class NoOpNotificationSender implements NotificationSenderInterface
{
    public function send(string $recipientId, string $subject, string $body): void
    {
        // No-op for tests
    }
}
