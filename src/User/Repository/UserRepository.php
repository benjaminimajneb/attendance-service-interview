<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\User\Model\User;

final class UserRepository
{
    public function getById(string $userId): ?User
    {
        // Stub: would query database
        return null;
    }
}
