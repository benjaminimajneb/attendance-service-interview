<?php

declare(strict_types=1);

namespace App\Controller;

use App\Notification\UserNotificationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class NotifyParentController
{
    public function __construct(
        private UserNotificationService $notificationService,
    ) {
    }

    public function notifyParent(Request $request): JsonResponse
    {
        $userId = $request->request->get('userId');
        $message = $request->request->get('message');

        $this->notificationService->sendNotification($userId, 'Parent Notification', $message);

        return new JsonResponse(['status' => 'sent'], Response::HTTP_OK);
    }
}
