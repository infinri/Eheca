<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Support\LoggerFactory;
use PDO;

/**
 * Handles displaying the contact form and processing submissions.
 *
 * This controller purposefully contains only contact-related concerns; all
 * notification logic is delegated to NotificationService to preserve SRP.
 */
class ContactController
{
    private Engine $view;
    private NotificationService $notifier;
    private PDO $db;

    public function __construct(Engine $view, NotificationService $notifier, PDO $db)
    {
        $this->view      = $view;
        $this->notifier  = $notifier;
        $this->db        = $db;
    }

    /**
     * Show the contact form.
     */
    public function showForm(Request $request, Response $response, array $args = []): Response
    {
        // Slim\Csrf\Guard sets the token itself in csrf_name / csrf_value attributes
        $csrfName     = $request->getAttribute('csrf_name');
        $csrfValue    = $request->getAttribute('csrf_value');

        // The field names that must be used in the form (Guard::getTokenNameKey() etc.)
        $data = [
            'title'          => 'Contact Us',
            'active'         => 'contact',
            'csrf_name_key'  => 'csrf_name',
            'csrf_value_key' => 'csrf_value',
            'csrf_name'      => $csrfName,
            'csrf_value'     => $csrfValue,
        ];

        try {
            $content = $this->view->render('contact', $data);
            $response->getBody()->write($content);
            return $response->withHeader('Content-Type', 'text/html');
        } catch (\Exception $e) {
            $error = 'Error rendering contact page: ' . $e->getMessage() . "\n" .
                'File: ' . $e->getFile() . ':' . $e->getLine() . "\n" .
                'Trace: ' . $e->getTraceAsString();

            LoggerFactory::get()->error($error);

            $response->getBody()->write('<pre>' . htmlspecialchars($error) . '</pre>');
            return $response->withStatus(500);
        }
    }

    /**
     * Handle contact form submission.
     */
    public function submitForm(Request $request, Response $response, array $args = []): Response
    {
        $data = $request->getParsedBody();

        // Validate ---------------------------------------------------------
        $errors = [];
        $requiredFields = [
            'name'           => 'Full Name',
            'email'          => 'Email',
            'project_type'   => 'Project Type',
            'message'        => 'Project Description',
            'privacy_policy' => 'Privacy Policy Agreement',
        ];
        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                $errors[$field] = "$label is required";
            }
        }
        if (empty($errors['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address';
        }
        if (!empty($data['phone'])) {
            $phone = preg_replace('/[^0-9+]/', '', $data['phone']);
            if (strlen($phone) < 10) {
                $errors['phone'] = 'Please enter a valid phone number';
            }
            $data['phone'] = $phone;
        }
        if (empty($errors['message']) && strlen($data['message']) < 10) {
            $errors['message'] = 'Please provide more details about your project (minimum 10 characters)';
        }
        $validProjectTypes = ['web', 'mobile', 'software', 'consulting', 'other'];
        if (empty($errors['project_type']) && !in_array($data['project_type'], $validProjectTypes, true)) {
            $errors['project_type'] = 'Please select a valid project type';
        }

        if (!empty($errors)) {
            $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => 'Please correct the errors in the form',
                    'errors'  => $errors,
                ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }

        // Build email body -------------------------------------------------
        $subject = 'New Contact Form Submission: ' . htmlspecialchars($data['name']);
        $rows    = [
            'Name'  => htmlspecialchars($data['name']),
            'Email' => htmlspecialchars($data['email']),
        ];
        if (!empty($data['phone'])) {
            $rows['Phone'] = htmlspecialchars($data['phone']);
        }
        if (!empty($data['company'])) {
            $rows['Company'] = htmlspecialchars($data['company']);
        }
        $projectTypes = [
            'web'        => 'Web Development',
            'mobile'     => 'Mobile App',
            'software'   => 'Software Development',
            'consulting' => 'Consulting',
            'other'      => 'Other',
        ];
        $rows['Project Type'] = $projectTypes[$data['project_type']] ?? htmlspecialchars($data['project_type']);
        if (!empty($data['contact_method'])) {
            $contactMethods = ['email' => 'Email', 'phone' => 'Phone', 'either' => 'Either is fine'];
            $rows['Preferred Contact Method'] = $contactMethods[$data['contact_method']] ?? htmlspecialchars($data['contact_method']);
        }
        if (!empty($data['source'])) {
            $sources = [
                'google'   => 'Google Search',
                'social'   => 'Social Media',
                'referral' => 'Referral',
                'event'    => 'Conference/Event',
                'other'    => 'Other',
            ];
            $rows['How did you hear about us?'] = $sources[$data['source']] ?? htmlspecialchars($data['source']);
        }

        $emailBody  = '<html><body>';
        $emailBody .= '<h2 style="font-family:Arial,Helvetica,sans-serif;">New Contact Form Submission</h2>';
        $emailBody .= '<table cellpadding="6" cellspacing="0" style="border-collapse:collapse;font-family:Arial,Helvetica,sans-serif;font-size:14px;">';
        foreach ($rows as $label => $value) {
            $emailBody .= '<tr><td style="border:1px solid #ddd;"><strong>' . $label . ':</strong></td><td style="border:1px solid #ddd;">' . $value . '</td></tr>';
        }
        $emailBody .= '<tr><td colspan="2" style="border:1px solid #ddd;"><strong>Message:</strong><br>' . nl2br(htmlspecialchars($data['message'])) . '</td></tr>';
        $emailBody .= '</table>';
        $emailBody .= '<p style="font-size:12px;color:#666;">This email was sent from the contact form on ' . ($_SERVER['HTTP_HOST'] ?? 'your website') . '.</p>';
        $emailBody .= '</body></html>';

        // Send via notifier ----------------------------------------------
        try {
            $this->notifier->sendContactForm($subject, $emailBody, strip_tags($data['message']));
        } catch (\Exception $e) {
            LoggerFactory::get()->error('Notification error: ' . $e->getMessage());
            $response->getBody()->write(json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }

        // Persist to DB ---------------------------------------------------
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO contact_submissions (
                    name, email, phone, company, project_type, contact_method, source, message, created_at
                ) VALUES (
                    :name, :email, :phone, :company, :project_type, :contact_method, :source, :message, :created_at
                )'
            );
            $stmt->execute([
                ':name'           => $data['name'],
                ':email'          => $data['email'],
                ':phone'          => $data['phone'] ?? null,
                ':company'        => $data['company'] ?? null,
                ':project_type'   => $data['project_type'],
                ':contact_method' => $data['contact_method'] ?? null,
                ':source'         => $data['source'] ?? null,
                ':message'        => $data['message'],
                ':created_at'     => date('c'),
            ]);
        } catch (\Throwable $e) {
            LoggerFactory::get()->error('DB insertion error: ' . $e->getMessage());
            // Continue without failing response
        }

        // Success ---------------------------------------------------------
        $response->getBody()->write(json_encode([
                'success' => true,
                'message' => 'Thank you for your message! We will get back to you soon.',
            ]));

        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
