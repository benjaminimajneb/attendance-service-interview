<?php

declare(strict_types=1);

namespace App\Attendance\Model;

final readonly class AttendanceRecord
{
    /**
     * @param array<string, bool> $studentStatuses student id => present (true) / absent (false)
     */
    public function __construct(
        public string $classId,
        public \DateTimeInterface $date,
        public array $studentStatuses,
    ) {
    }
}
