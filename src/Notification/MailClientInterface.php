<?php

declare(strict_types=1);

namespace App\Notification;

interface MailClientInterface
{
    public function sendEmail(string $to, string $subject, string $body): void;
}
