<?php
require __DIR__ . '/vendor/autoload.php';

use Brevo\Client\Api\AccountApi;
use Brevo\Client\Api\SendersApi;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;
use Brevo\Client\Model\SendSmtpEmailTo;
use Brevo\Client\Model\SendSmtpEmailSender;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Configure API key
$config = Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', $_ENV['BREVO_API_KEY']);

// 1. Check account status
try {
    echo "=== Checking Account Status ===\n";
    $accountApi = new AccountApi(new GuzzleHttp\Client(), $config);
    $account = $accountApi->getAccount();
    echo "Plan: " . $account->getPlan()[0]->getType() . "\n";
    echo "Credits: " . $account->getPlan()[0]->getCredits() . "\n";
    echo "Email: " . $account->getEmail() . "\n";
} catch (Exception $e) {
    echo "Account API Error: " . $e->getMessage() . "\n";
}

// 2. Check verified senders
try {
    echo "\n=== Checking Verified Senders ===\n";
    $sendersApi = new SendersApi(new GuzzleHttp\Client(), $config);
    $senders = $sendersApi->getSenders();
    foreach ($senders->getSenders() as $sender) {
        echo "Sender: " . $sender->getEmail() . " (Status: " . $sender->getIps()[0]->getStatus() . ")\n";
    }
} catch (Exception $e) {
    echo "Senders API Error: " . $e->getMessage() . "\n";
}

// 3. Send a test email to a different address
try {
    echo "\n=== Sending Test Email ===\n";
    $api = new TransactionalEmailsApi(new GuzzleHttp\Client(), $config);
    
    $sendSmtpEmail = new SendSmtpEmail([
        'subject' => 'Brevo API Test - ' . date('Y-m-d H:i:s'),
        'sender' => new SendSmtpEmailSender([
            'email' => $_ENV['MAIL_FROM_ADDRESS'],
            'name' => $_ENV['MAIL_FROM_NAME']
        ]),
        'to' => [
            new SendSmtpEmailTo([
                'email' => $_ENV['ADMIN_EMAIL'], // Change this to a different email
                'name' => 'Test Recipient'
            ])
        ],
        'htmlContent' => '<h1>Brevo API Test</h1><p>This is a test email sent via Brevo API.</p>',
        'textContent' => 'This is a test email sent via Brevo API.',
    ]);

    $result = $api->sendTransacEmail($sendSmtpEmail);
    echo "Test email sent! Message ID: " . $result->getMessageId() . "\n";
} catch (Exception $e) {
    echo "Send Email Error: " . $e->getMessage() . "\n";
    if ($e->getResponseBody()) {
        echo "Details: " . $e->getResponseBody() . "\n";
    }
}
