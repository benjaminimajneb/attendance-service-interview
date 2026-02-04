<?php

declare(strict_types=1);

namespace App\Notification;

use App\User\Model\User;

/**
 * Service for sending notifications to users.
 */
final class UserNotificationService
{
    public function __construct(
        private SendGridMailClient $mailClient,
    ) {
    }

    public function sendNotification(User $user, string $subject, string $body): void
    {
        if (!$user->isParent) {
            throw new \InvalidArgumentException('User is not a parent');
        }

        $this->mailClient->sendEmail($user->email, $subject, $body);
    }
}
