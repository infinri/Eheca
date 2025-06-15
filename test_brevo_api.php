<?php
require __DIR__ . '/vendor/autoload.php';

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Model\SendSmtpEmailTo;
use Brevo\Client\Model\SendSmtpEmailSender;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configure API key
$config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['BREVO_API_KEY']);

$apiInstance = new TransactionalEmailsApi(
    new GuzzleHttp\Client(),
    $config
);

// Create the email
$sendSmtpEmail = new SendSmtpEmail([
    'subject' => 'Test Email from Eheca (API)',
    'sender' => new SendSmtpEmailSender([
        'email' => $_ENV['MAIL_FROM_ADDRESS'],
        'name' => $_ENV['MAIL_FROM_NAME']
    ]),
    'to' => [
        new SendSmtpEmailTo([
            'email' => $_ENV['ADMIN_EMAIL'],
            'name' => 'Admin'
        ])
    ],
    'htmlContent' => '<h1>This is a test email from Eheca using Brevo API</h1><p>If you can read this, the API integration is working!</p>',
    'textContent' => 'This is a test email from Eheca using Brevo API',
]);

try {
    $result = $apiInstance->sendTransacEmail($sendSmtpEmail);
    echo "Email sent successfully! Message ID: " . $result->getMessageId() . "\n";
} catch (Exception $e) {
    echo 'Exception when calling TransactionalEmailsApi->sendTransacEmail: ', $e->getMessage(), PHP_EOL;
    if ($e->getResponseBody()) {
        echo 'Error details: ' . $e->getResponseBody() . PHP_EOL;
    }
}
