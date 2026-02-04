<?php

declare(strict_types=1);

namespace App\Attendance\Repository;

use App\Attendance\Model\AttendanceRecord;

interface AttendanceRepositoryInterface
{
    public function save(AttendanceRecord $record): void;

    /**
     * @return array<string, bool> student id => present (true) / absent (false)
     */
    public function getByClassAndDate(string $classId, \DateTimeInterface $date): ?array;
}
