<?php

declare(strict_types=1);

namespace App\Attendance\Queue;

interface QueueClientInterface
{
    /**
     * @param array<string, mixed> $payload
     */
    public function enqueue(string $queueName, array $payload): void;
}
