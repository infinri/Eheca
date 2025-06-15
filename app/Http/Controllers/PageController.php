<?php

namespace App\Http\Controllers;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Support\LoggerFactory;

/**
 * Handles static informational pages (FAQ, About, Examples, Services).
 */
class PageController
{
    private Engine $view;

    public function __construct(Engine $view)
    {
        $this->view = $view;
    }

    public function faq(Request $request, Response $response, array $args = []): Response
    {
        $data = [
            'title' => 'FAQ & How I Work',
            'active' => 'faq',
            'faqs'  => [
                [
                    'question' => "What's your typical process for handling a task?",
                    'answer'   => '1) Quick assessment 2) Immediate confirmation & timeline 3) Focused implementation 4) Delivery with docs.'
                ],
                [
                    'question' => 'How fast do you respond?',
                    'answer'   => 'Within 1 business hour (09:00-17:00 EST). Expedited options available.'
                ],
                [
                    'question' => 'Rates?',
                    'answer'   => 'Transparent task-based pricing. Most micro-tasks start at $99.'
                ],
                [
                    'question' => 'How do you ensure quality & security?',
                    'answer'   => 'Best-practice coding, automated tests, manual review, and security scanning.'
                ],
            ],
        ];
        return $this->render('faq', $data, $response);
    }

    public function about(Request $request, Response $response, array $args = []): Response
    {
        $data = [
            'title' => 'About Me',
            'active' => 'about',
            'bio'   => [
                'intro'      => "I'm a rebel indie dev who gets things DONE.",
                'philosophy' => 'Clean code, clear communication, zero fluff.',
                'qualities'  => [
                    'Fast turnaround',
                    'Reliable delivery',
                    'Transparent process',
                    'Security-first mindset',
                ],
            ],
        ];
        return $this->render('about', $data, $response);
    }

    public function examples(Request $request, Response $response, array $args = []): Response
    {
        $data = [
            'title'    => 'Example Tasks',
            'active'   => 'examples',
            'examples' => [
                [
                    'title'       => 'API Integration Fix',
                    'description' => 'Restored broken payment gateway on Magento store',
                    'time'        => '2 h',
                    'result'      => 'Recovered $5k/day revenue',
                    'tech'        => ['PHP', 'REST', 'Stripe'],
                ],
                [
                    'title'       => 'Performance Boost',
                    'description' => 'Reduced page load 8s → 1s',
                    'time'        => '3 h',
                    'result'      => '+38% conversion',
                    'tech'        => ['JS', 'Webpack', 'Cache'],
                ],
            ],
        ];
        return $this->render('examples', $data, $response);
    }

    public function services(Request $request, Response $response, array $args = []): Response
    {
        $data = [
            'title'    => 'Services',
            'active'   => 'services',
            'services' => [
                'Frontend' => [
                    'Responsive UI fixes',
                    'Vue/React component work',
                    'Performance & bundle optimisation',
                ],
                'Backend'  => [
                    'API integrations',
                    'Database design & migration',
                    'Cron & background job setup',
                ],
                'Magento'  => [
                    'Module bug fixes',
                    'Checkout customisation',
                    'Upgrade & security patches',
                ],
            ],
        ];
        return $this->render('services', $data, $response);
    }

    /** Render helper */
    private function render(string $tpl, array $data, Response $response): Response
    {
        try {
            $content = $this->view->render($tpl, $data);
            $response->getBody()->write($content);
            return $response->withHeader('Content-Type', 'text/html');
        } catch (\Throwable $e) {
            LoggerFactory::get()->error("Render error ({$tpl}): " . $e->getMessage());
            $response->getBody()->write('<pre>Error loading page.</pre>');
            return $response->withStatus(500);
        }
    }
}
