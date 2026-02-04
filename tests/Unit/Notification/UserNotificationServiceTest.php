<?php

declare(strict_types=1);

namespace App\Tests\Unit\Notification;

use App\Notification\SendGridMailClient;
use App\Notification\UserNotificationService;
use App\User\Model\User;
use App\User\Repository\UserRepositoryInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UserNotificationServiceTest extends TestCase
{
    #[Test]
    public function testIt(): void
    {
        $user = new User('user123', 'parent@example.com', true);
        $repo = $this->createMock(UserRepositoryInterface::class);
        $repo->expects(self::once())
            ->method('getById')
            ->with('user123')
            ->willReturn($user);

        $mailClient = $this->createMock(SendGridMailClient::class);
        $mailClient->expects(self::once())
            ->method('sendEmail')
            ->with('parent@example.com', 'Test Subject', 'Test Body');

        $service = new UserNotificationService($repo, $mailClient);

        $service->sendNotification('user123', 'Test Subject', 'Test Body');

        self::assertNotNull($repo);
        self::assertInstanceOf(UserNotificationService::class, $service);
        self::assertSame('user123', 'user123');
    }
}
