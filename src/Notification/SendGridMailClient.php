<?php

declare(strict_types=1);

namespace App\Notification;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * SendGrid mail provider client implementation.
 */
final class SendGridMailClient implements MailClientInterface
{
    private string $apiKey;
    private Client $httpClient;

    public function __construct()
    {
        $configService = new ConfigService();
        $this->apiKey = $configService->get('SENDGRID_API_KEY') ?: 'dummy-sendgrid-key';
        $this->httpClient = new Client();
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        if ($this->apiKey === 'dummy-sendgrid-key') {
            // For demo purposes, if API key is dummy, just log
            error_log(sprintf('Simulating SendGrid email to %s: %s - %s', $to, $subject, $body));
            return;
        }

        $url = 'https://api.sendgrid.com/v3/mail/send';
        $payload = [
            'personalizations' => [
                [
                    'to' => [['email' => $to]],
                ],
            ],
            'from' => ['email' => 'noreply@example.com'],
            'subject' => $subject,
            'content' => [
                [
                    'type' => 'text/plain',
                    'value' => $body,
                ],
            ],
        ];

        try {
            $response = $this->httpClient->post($url, [
                'json' => $payload,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode === 202) {
                return;
            }

            $this->handleErrorResponse($statusCode, $response->getBody()->getContents());
        } catch (GuzzleException $e) {
            $this->handleRequestException($e);
        }
    }

    private function handleErrorResponse(int $statusCode, string $responseBody): void
    {
        if ($statusCode >= 400 && $statusCode < 500) {
            throw new \RuntimeException(sprintf('SendGrid client error: HTTP %d - %s', $statusCode, $responseBody));
        }

        if ($statusCode >= 500) {
            throw new \RuntimeException(sprintf('SendGrid server error: HTTP %d - %s', $statusCode, $responseBody));
        }

        throw new \RuntimeException(sprintf('Unexpected SendGrid response: HTTP %d - %s', $statusCode, $responseBody));
    }

    private function handleRequestException(GuzzleException $e): void
    {
        $message = sprintf('Failed to send email via SendGrid: %s', $e->getMessage());
        throw new \RuntimeException($message, 0, $e);
    }
}
