<?php

declare(strict_types=1);

namespace App\Tests\Unit\Notification;

use App\Notification\SendGridMailClient;
use App\Notification\UserNotificationService;
use App\User\Model\User;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class UserNotificationServiceTest extends TestCase
{
    #[Test]
    public function testItSendsNotificationToParentUser(): void
    {
        $mockMailClient = $this->createMock(SendGridMailClient::class);
        $mockMailClient->expects(self::once())
            ->method('sendEmail')
            ->with(
                self::equalTo('parent@example.com'),
                self::equalTo('Parent Notification'),
                self::equalTo('Test message')
            );

        $service = new UserNotificationService($mockMailClient);
        self::assertInstanceOf(UserNotificationService::class, $service);

        $parentUser = new User('parent1', 'parent@example.com', true);

        $service->sendNotification($parentUser, 'Parent Notification', 'Test message');

        $mockClient = $this->createMock(SendGridMailClient::class);
        $mockClient->expects(self::once())
            ->method('sendEmail')
            ->willThrowException(new \RuntimeException('Invalid email'));

        $service = new UserNotificationService($mockClient);
        $invalidEmailUser = new User('parent2', 'invalid-email', true);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid email');
        $service->sendNotification($invalidEmailUser, 'Parent Notification', 'Test message');

    }
}
