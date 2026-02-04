<?php

declare(strict_types=1);

namespace App\Attendance\Repository;

use App\Attendance\Model\AttendanceRecord;
use App\Database\DatabaseInterface;

final class AttendanceRepository
{
    public function __construct(
        private DatabaseInterface $database,
    ) {
    }

    public function save(AttendanceRecord $record): void
    {
        $this->database->insert('attendance_records', [
            'class_id' => $record->classId,
            'date' => $record->date->format('Y-m-d'),
            'student_statuses' => json_encode($record->studentStatuses),
        ]);
    }

    /**
     * @return array<string, bool> student id => present (true) / absent (false)
     */
    public function getByClassAndDate(string $classId, \DateTimeInterface $date): ?array
    {
        $record = $this->database->findOne('attendance_records', [
            'class_id' => $classId,
            'date' => $date->format('Y-m-d'),
        ]);

        if ($record === null) {
            return null;
        }

        $studentStatuses = json_decode($record['student_statuses'], true);
        if (!is_array($studentStatuses)) {
            return null;
        }

        return $studentStatuses;
    }
}
