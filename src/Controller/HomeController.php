<?php

namespace Eheca\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'features' => [
                [
                    'icon' => '⚡',
                    'title' => 'Blazing Fast',
                    'description' => 'Built with performance in mind. Eheca is optimized for speed and efficiency.'
                ],
                [
                    'icon' => '🔒',
                    'title' => 'Secure by Default',
                    'description' => 'Security best practices built-in to protect your application.'
                ],
                [
                    'icon' => '🧩',
                    'title' => 'Modular Architecture',
                    'description' => 'Build scalable applications with our modular component system.'
                ],
                [
                    'icon' => '🚀',
                    'title' => 'Developer Friendly',
                    'description' => 'Intuitive API and comprehensive documentation to get you started quickly.'
                ],
            ]
        ]);
    }
}
