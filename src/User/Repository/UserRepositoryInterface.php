<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\User\Model\User;

interface UserRepositoryInterface
{
    public function getById(string $userId): ?User;
}
