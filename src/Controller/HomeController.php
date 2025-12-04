<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'page_title' => 'Symfony Pro - Modern Web Development',
            'features' => [
                [
                    'icon' => '⚡',
                    'title' => 'High Performance',
                    'description' => 'Built with Symfony components for blazing fast performance',
                    'color' => 'primary'
                ],
                [
                    'icon' => '🛡️',
                    'title' => 'Enterprise Security',
                    'description' => 'Industry-leading security features and best practices',
                    'color' => 'success'
                ],
                [
                    'icon' => '🔌',
                    'title' => 'Modular Architecture',
                    'description' => 'Flexible and scalable component-based structure',
                    'color' => 'info'
                ],
                [
                    'icon' => '📱',
                    'title' => 'Responsive Design',
                    'description' => 'Fully responsive and mobile-friendly interface',
                    'color' => 'warning'
                ],
                [
                    'icon' => '🚀',
                    'title' => 'Rapid Development',
                    'description' => 'Powerful CLI tools for faster development cycles',
                    'color' => 'danger'
                ],
                [
                    'icon' => '🌐',
                    'title' => 'API Ready',
                    'description' => 'Built-in support for RESTful APIs and microservices',
                    'color' => 'dark'
                ]
            ],
            'stats' => [
                ['value' => '99.9%', 'label' => 'Uptime'],
                ['value' => '2.5x', 'label' => 'Faster Development'],
                ['value' => '50+', 'label' => 'Built-in Components'],
                ['value' => '1000+', 'label' => 'Community Packages']
            ],
            'quick_links' => [
                ['name' => 'Dashboard', 'path' => '#', 'icon' => '📊'],
                ['name' => 'Documentation', 'path' => '#', 'icon' => '📚'],
                ['name' => 'API Explorer', 'path' => '#', 'icon' => '🔍'],
                ['name' => 'Admin Panel', 'path' => '#', 'icon' => '⚙️']
            ]
        ]);
    }
}
