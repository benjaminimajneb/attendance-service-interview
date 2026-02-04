<?php

declare(strict_types=1);

namespace App\Attendance\Repository;

use App\Attendance\Model\AttendanceRecord;

final class AttendanceRepository
{
    public function save(AttendanceRecord $record): void
    {
        // Stub: would persist to database
    }

    /**
     * @return array<string, bool> student id => present (true) / absent (false)
     */
    public function getByClassAndDate(string $classId, \DateTimeInterface $date): ?array
    {
        // Stub: would query database
        return null;
    }
}
