<?php

declare(strict_types=1);

namespace App\Attendance\Service;

use App\Attendance\Model\AttendanceRecord;
use App\Attendance\Model\AttendanceSubmission;
use App\Attendance\Queue\QueueClientInterface;

/**
 * Submits attendance by enqueueing a job for async processing (keeps response within SLO).
 */
final class AttendanceSubmissionService
{
    private const QUEUE_NAME = 'attendance';

    public function __construct(
        private QueueClientInterface $queue,
    ) {
    }

    /**
     * Validates and enqueues attendance for processing. Returns a job id for acknowledgment.
     */
    public function submit(AttendanceSubmission $submission): string
    {
        $this->validate($submission);
        $jobId = $this->generateJobId();
        $this->queue->enqueue(self::QUEUE_NAME, [
            'job_id' => $jobId,
            'class_id' => $submission->classId,
            'date' => $submission->date->format('Y-m-d'),
            'student_statuses' => $submission->studentStatuses,
        ]);
        return $jobId;
    }

    private function validate(AttendanceSubmission $submission): void
    {
        if ($submission->classId === '') {
            throw new \InvalidArgumentException('class_id cannot be empty');
        }
        if (empty($submission->studentStatuses)) {
            throw new \InvalidArgumentException('student_statuses cannot be empty');
        }
        foreach ($submission->studentStatuses as $studentId => $present) {
            if (!is_string($studentId) || $studentId === '') {
                throw new \InvalidArgumentException('Invalid student id in student_statuses');
            }
            if (!is_bool($present)) {
                throw new \InvalidArgumentException('Each student status must be boolean');
            }
        }
    }

    private function generateJobId(): string
    {
        return uniqid('attendance_', true);
    }
}
