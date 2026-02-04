<?php

declare(strict_types=1);

namespace App\User\Repository;

use App\Database\DatabaseInterface;
use App\User\Model\User;

final class UserRepository
{
    public function __construct(
        private DatabaseInterface $database,
    ) {
    }

    public function getById(string $userId): ?User
    {
        $record = $this->database->findOne('users', [
            'id' => $userId,
        ]);

        if ($record === null) {
            return null;
        }

        return new User(
            $record['id'],
            $record['email'],
            (bool) $record['is_parent'],
        );
    }
}
