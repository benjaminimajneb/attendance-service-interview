<?php

declare(strict_types=1);

namespace App\Controller;

use App\Notification\UserNotificationService;
use App\User\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class NotifyParentController
{
    public function __construct(
        private UserNotificationService $notificationService,
        private UserRepository $userRepository,
    ) {
    }

    public function notifyParent(Request $request): JsonResponse
    {
        $userId = $request->request->get('userId');
        $message = $request->request->get('message');

        // Resolve user from ID in the controller
        $user = $this->userRepository->getById($userId);
        if ($user === null) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $this->notificationService->sendNotification($user, 'Parent Notification', $message);

        return new JsonResponse(['status' => 'sent'], Response::HTTP_OK);
    }
}
