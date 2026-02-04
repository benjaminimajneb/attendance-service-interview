<?php

declare(strict_types=1);

namespace App\Notification;

use App\User\Model\User;
use App\User\Repository\UserRepositoryInterface;

/**
 * Service for sending notifications to users.
 */
final class UserNotificationService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private SendGridMailClient $mailClient,
    ) {
    }

    public function sendNotification(string $userId, string $subject, string $body): void
    {
        $user = $this->userRepository->getById($userId);
        if ($user === null) {
            throw new \InvalidArgumentException('User not found');
        }

        if (!$user->isParent) {
            throw new \InvalidArgumentException('User is not a parent');
        }

        $this->mailClient->sendEmail($user->email, $subject, $body);
    }
}
