<?php

declare(strict_types=1);

namespace App\Export;

use App\Attendance\Model\AttendanceRecord;

final class NullExportClient implements AttendanceExportClientInterface
{
    public function export(AttendanceRecord $record): void
    {
        // No-op for tests
    }
}
