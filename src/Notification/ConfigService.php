<?php

declare(strict_types=1);

namespace App\Notification;

/**
 * Service for retrieving configuration values.
 */
final class ConfigService
{
    public function get(string $key): string
    {
        $value = getenv($key);
        if ($value === false) {
            return '';
        }
        return $value;
    }
}
