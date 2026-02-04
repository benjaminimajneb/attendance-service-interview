<?php

declare(strict_types=1);

namespace App\Export;

use App\Attendance\Model\AttendanceRecord;

interface AttendanceExportClientInterface
{
    public function export(AttendanceRecord $record): void;
}
