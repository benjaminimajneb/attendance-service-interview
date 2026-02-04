<?php

declare(strict_types=1);

namespace App\Attendance\Service;

use App\Attendance\Model\AttendanceRecord;
use App\Attendance\Repository\AttendanceRepositoryInterface;
use App\Notification\EmailNotificationSender;
use App\Notification\MailerConfig;

/**
 * Records attendance for a class and emails the teacher.
 */
final class RecordAttendanceAndNotifyUser
{
    public function __construct(
        private AttendanceRepositoryInterface $repo,
    ) {
    }

    public function recordAndNotify(string $classId, string $dateStr, array $studentStatuses, string $teacherEmail): void
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

        $mailer = new EmailNotificationSender(MailerConfig::fromEnv());
        $mailer->send($teacherEmail, 'Attendance recorded', 'Class ' . $classId . ' attendance for ' . $dateStr . ' has been recorded.');
    }
}
