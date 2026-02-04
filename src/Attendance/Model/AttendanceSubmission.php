<?php

declare(strict_types=1);

namespace App\Attendance\Model;

/**
 * DTO for an incoming attendance submission request.
 *
 * @param array<string, bool> $studentStatuses student id => present (true) / absent (false)
 */
final readonly class AttendanceSubmission
{
    public function __construct(
        public string $classId,
        public \DateTimeInterface $date,
        public array $studentStatuses,
    ) {
    }
}
