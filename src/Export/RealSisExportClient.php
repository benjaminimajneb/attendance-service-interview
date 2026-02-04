<?php

declare(strict_types=1);

namespace App\Export;

use App\Attendance\Model\AttendanceRecord;

final class RealSisExportClient implements AttendanceExportClientInterface
{
    public function __construct(
        private ExportClientConfig $config,
    ) {
    }

    public function export(AttendanceRecord $record): void
    {
        if ($this->config->baseUrl === '') {
            throw new \RuntimeException('Export client not configured');
        }
        // Stub: would POST to $this->config->baseUrl with record payload
    }
}
