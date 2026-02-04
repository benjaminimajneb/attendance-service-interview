<?php

declare(strict_types=1);

namespace App\Notification;

interface NotificationSenderInterface
{
    public function send(string $recipientId, string $subject, string $body): void;
}
