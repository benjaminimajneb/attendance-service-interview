<?php

declare(strict_types=1);

namespace App\Attendance\Service;

use App\Attendance\Model\AttendanceRecord;
use App\Attendance\Repository\AttendanceRepository;

/**
 * Processes a single attendance job from the queue (persists via repository).
 */
final class AttendanceJobHandler
{
    public function __construct(
        private AttendanceRepository $repository,
    ) {
    }

    /**
     * @param array<string, mixed> $payload Must contain class_id, date, student_statuses
     */
    public function handle(array $payload): void
    {
        $classId = $payload['class_id'] ?? null;
        $dateStr = $payload['date'] ?? null;
        $studentStatuses = $payload['student_statuses'] ?? null;

        if (!is_string($classId) || $classId === '') {
            throw new \InvalidArgumentException('Missing or invalid class_id in job payload');
        }
        if (!is_string($dateStr) || $dateStr === '') {
            throw new \InvalidArgumentException('Missing or invalid date in job payload');
        }
        if (!is_array($studentStatuses)) {
            throw new \InvalidArgumentException('Missing or invalid student_statuses in job payload');
        }

        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $dateStr);
        if ($date === false) {
            throw new \InvalidArgumentException('Invalid date format in job payload');
        }

        $record = new AttendanceRecord($classId, $date, $studentStatuses);
        $this->repository->save($record);
    }
}
