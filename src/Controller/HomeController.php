<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for rendering the home page.
 */
final class HomeController extends AbstractController
{
    /**
     * Display the homepage.
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Render the 'home/index.html.twig' template with default parameters
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
