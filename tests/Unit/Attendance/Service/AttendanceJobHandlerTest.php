<?php

declare(strict_types=1);

namespace App\Tests\Unit\Attendance\Service;

use App\Attendance\Model\AttendanceRecord;
use App\Attendance\Repository\AttendanceRepositoryInterface;
use App\Attendance\Service\AttendanceJobHandler;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AttendanceJobHandlerTest extends TestCase
{
    #[Test]
    public function handle_saves_record_via_repository(): void
    {
        $repository = $this->createMock(AttendanceRepositoryInterface::class);
        $repository->expects(self::once())
            ->method('save')
            ->with(self::callback(function (AttendanceRecord $record): bool {
                return $record->classId === 'class-1'
                    && $record->date->format('Y-m-d') === '2025-02-04'
                    && $record->studentStatuses === ['student-1' => true, 'student-2' => false];
            }));

        $handler = new AttendanceJobHandler($repository);
        $payload = [
            'class_id' => 'class-1',
            'date' => '2025-02-04',
            'student_statuses' => ['student-1' => true, 'student-2' => false],
        ];

        $handler->handle($payload);
    }

    #[Test]
    public function handle_throws_when_class_id_missing(): void
    {
        $repository = $this->createStub(AttendanceRepositoryInterface::class);
        $handler = new AttendanceJobHandler($repository);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing or invalid class_id');
        $handler->handle(['date' => '2025-02-04', 'student_statuses' => ['s1' => true]]);
    }

    #[Test]
    public function handle_throws_when_date_invalid(): void
    {
        $repository = $this->createStub(AttendanceRepositoryInterface::class);
        $handler = new AttendanceJobHandler($repository);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date format');
        $handler->handle([
            'class_id' => 'class-1',
            'date' => 'not-a-date',
            'student_statuses' => ['s1' => true],
        ]);
    }
}
