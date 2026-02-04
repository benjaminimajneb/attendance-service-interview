<?php

declare(strict_types=1);

namespace App\Attendance\Service;

use App\Attendance\Model\AttendanceRecord;
use App\Attendance\Repository\AttendanceRepositoryInterface;
use App\Export\ExportClientConfig;
use App\Export\RealSisExportClient;

/**
 * Records attendance for a class and exports it to an external system.
 */
final class RecordAttendanceAndExport
{
    public function __construct(
        private AttendanceRepositoryInterface $repo,
    ) {
    }

    public function recordAndExport(string $classId, string $dateStr, array $studentStatuses): void
    {
        if ($classId === '') {
            throw new \InvalidArgumentException('class id required');
        }
        $date = \DateTimeImmutable::createFromFormat('Y-m-d', $dateStr);
        if ($date === false) {
            throw new \InvalidArgumentException('invalid date');
        }
        if (empty($studentStatuses)) {
            throw new \InvalidArgumentException('student statuses required');
        }

        $record = new AttendanceRecord($classId, $date, $studentStatuses);
        $this->repo->save($record);

        $exportClient = new RealSisExportClient(ExportClientConfig::fromEnv());
        $exportClient->export($record);
    }
}
