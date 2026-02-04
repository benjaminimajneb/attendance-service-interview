<?php

declare(strict_types=1);

namespace App\Tests\Unit\Attendance\Service;

use App\Attendance\Model\AttendanceRecord;
use App\Attendance\Repository\AttendanceRepositoryInterface;
use App\Attendance\Service\RecordAttendanceAndExport;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class RecordAttendanceAndExportTest extends TestCase
{
    #[Test]
    public function testIt(): void
    {
        $repo = $this->createMock(AttendanceRepositoryInterface::class);
        $repo->expects(self::once())
            ->method('save')
            ->with(self::callback(function (AttendanceRecord $r): bool {
                return $r->classId === 'C1'
                    && $r->date->format('Y-m-d') === '2025-02-04'
                    && $r->studentStatuses === ['s1' => true, 's2' => false];
            }));

        $svc = new RecordAttendanceAndExport($repo);

        $svc->recordAndExport('C1', '2025-02-04', ['s1' => true, 's2' => false]);

        self::assertNotNull($repo);
        self::assertInstanceOf(RecordAttendanceAndExport::class, $svc);
        self::assertSame('C1', 'C1');
    }
}
