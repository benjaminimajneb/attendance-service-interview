<?php

declare(strict_types=1);

namespace App\Export;

use App\Attendance\Model\AttendanceRecord;
use Psr\Log\LoggerInterface;

final class LogOnlyExportClient implements AttendanceExportClientInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function export(AttendanceRecord $record): void
    {
        $this->logger->info('Export (log only)', [
            'classId' => $record->classId,
            'date' => $record->date->format('Y-m-d'),
        ]);
    }
}
