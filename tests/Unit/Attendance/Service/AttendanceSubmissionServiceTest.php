<?php

declare(strict_types=1);

namespace App\Tests\Unit\Attendance\Service;

use App\Attendance\Model\AttendanceSubmission;
use App\Attendance\Queue\QueueClientInterface;
use App\Attendance\Service\AttendanceSubmissionService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AttendanceSubmissionServiceTest extends TestCase
{
    #[Test]
    public function submit_enqueues_payload_with_class_date_and_student_statuses(): void
    {
        $queue = $this->createMock(QueueClientInterface::class);
        $queue->expects(self::once())
            ->method('enqueue')
            ->with(
                'attendance',
                self::callback(function (array $payload): bool {
                    return isset($payload['job_id'], $payload['class_id'], $payload['date'], $payload['student_statuses'])
                        && str_starts_with($payload['job_id'], 'attendance_')
                        && $payload['class_id'] === 'class-1'
                        && $payload['date'] === '2025-02-04'
                        && $payload['student_statuses'] === ['student-1' => true, 'student-2' => false];
                })
            );

        $service = new AttendanceSubmissionService($queue);
        $submission = new AttendanceSubmission(
            'class-1',
            new \DateTimeImmutable('2025-02-04'),
            ['student-1' => true, 'student-2' => false],
        );

        $jobId = $service->submit($submission);

        self::assertNotEmpty($jobId);
        self::assertStringStartsWith('attendance_', $jobId);
    }

    #[Test]
    public function submit_throws_when_class_id_empty(): void
    {
        $queue = $this->createStub(QueueClientInterface::class);
        $service = new AttendanceSubmissionService($queue);
        $submission = new AttendanceSubmission('', new \DateTimeImmutable('2025-02-04'), ['s1' => true]);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('class_id cannot be empty');
        $service->submit($submission);
    }

    #[Test]
    public function submit_throws_when_student_statuses_empty(): void
    {
        $queue = $this->createStub(QueueClientInterface::class);
        $service = new AttendanceSubmissionService($queue);
        $submission = new AttendanceSubmission('class-1', new \DateTimeImmutable('2025-02-04'), []);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('student_statuses cannot be empty');
        $service->submit($submission);
    }
}
