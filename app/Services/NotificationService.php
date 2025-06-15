<?php

namespace App\Services;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration as BrevoConfiguration;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Model\SendSmtpEmailSender;
use Brevo\Client\Model\SendSmtpEmailTo;

/**
 * NotificationService isolates email (and future SMS) sending logic.
 * This keeps controllers focused on HTTP concerns.
 */
class NotificationService
{
    private TransactionalEmailsApi $api;
    private string $senderEmail;
    private string $senderName;
    private string $defaultRecipient;

    public function __construct()
    {
        // Load Brevo API key
        // Prefer getenv() for compatibility with production setups that use real environment variables.
        // Fallback to the populated $_ENV super-global (populated by php-dotenv) when getenv() returns empty.
        $brevoApiKey = getenv('BREVO_API_KEY');
        if ($brevoApiKey === false || $brevoApiKey === '') {
            $brevoApiKey = $_ENV['BREVO_API_KEY'] ?? '';
        }
        if (empty($brevoApiKey)) {
            throw new \RuntimeException('BREVO_API_KEY is not set in the environment.');
        }

        $config = BrevoConfiguration::getDefaultConfiguration()->setApiKey('api-key', $brevoApiKey);
        $this->api = new TransactionalEmailsApi(new \GuzzleHttp\Client(), $config);

        // Sender details (must be a Brevo-verified sender)
        $this->senderEmail = getenv('MAIL_FROM_ADDRESS') ?: 'lilith@eheca.net';
        if (!filter_var($this->senderEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException('MAIL_FROM_ADDRESS is not a valid email address');
        }
        $this->senderName = getenv('MAIL_FROM_NAME') ?: 'Lilith';

        // Default recipient (site admin)
        $this->defaultRecipient = getenv('ADMIN_EMAIL') ?: 'infinri@gmail.com';
    }

    /**
     * Send a contact-form email.
     *
     * @param string      $subject      Email subject
     * @param string      $htmlContent  HTML body
     * @param string      $textContent  Plain-text body
     * @param string|null $to           Recipient email (defaults to env ADMIN_EMAIL)
     */
    public function sendContactForm(string $subject, string $htmlContent, string $textContent, ?string $to = null): void
    {
        $to = $to ?: $this->defaultRecipient;

        // If no real API key (common in local dev), short-circuit and pretend send succeeded.
        $apiKeyCheck = getenv('BREVO_API_KEY');
        if ($apiKeyCheck === false || $apiKeyCheck === '') {
            $apiKeyCheck = $_ENV['BREVO_API_KEY'] ?? '';
        }
        if (empty($apiKeyCheck) || $apiKeyCheck === 'dummy') {
            // Log and return without external call
            \App\Support\LoggerFactory::get()->info('NotificationService: skipped email send – BREVO_API_KEY not found or is dummy.');
            return;
        }

        // Build the email payload early so the closure captures only simple data.
        $emailPayload = [
            'subject'      => $subject,
            'sender'       => [
                'email' => $this->senderEmail,
                'name'  => $this->senderName,
            ],
            'to'           => [
                [
                    'email' => $to,
                    'name'  => 'Admin',
                ],
            ],
            'htmlContent'  => $htmlContent,
            'textContent'  => $textContent,
        ];

        // Closure that actually sends the email.
        $send = function () use ($emailPayload): void {
            $email = new SendSmtpEmail([
                'subject'      => $emailPayload['subject'],
                'sender'       => new SendSmtpEmailSender($emailPayload['sender']),
                'to'           => array_map(fn ($t) => new SendSmtpEmailTo($t), $emailPayload['to']),
                'htmlContent'  => $emailPayload['htmlContent'],
                'textContent'  => $emailPayload['textContent'],
            ]);

            // Use the already-configured API instance from the service
            $this->api->sendTransacEmail($email);
        };

        // Prefer fastcgi_finish_request() for immediate flush under PHP-FPM.
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
            $send();
        } else {
            // Fallback: execute after response is sent.
            register_shutdown_function($send);
        }
    }
}
