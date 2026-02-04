<?php

declare(strict_types=1);

namespace App\Notification;

/**
 * SendGrid mail provider client implementation.
 */
final class SendGridMailClient implements MailClientInterface
{
    private string $apiKey;

    public function __construct()
    {
        $configs = new ConfigService();
        $this->apiKey = $configs->get('SENDGRID_API_KEY');
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        if ($this->apiKey === '') {
            throw new \RuntimeException('SendGrid API key not configured');
        }
        // Stub: would send email via SendGrid API using $this->apiKey
    }
}
