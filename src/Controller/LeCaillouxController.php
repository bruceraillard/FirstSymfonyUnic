<?php

namespace App\Controller;

use App\Repository\LeCaillouxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for handling "Le Cailloux" pages.
 */
#[Route('/lecailloux', name: 'lecailloux_')]
final class LeCaillouxController extends AbstractController
{
    /**
     * Render the Le Cailloux home page.
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Render the main index template for Le Cailloux
        return $this->render('le_cailloux/index.html.twig', [
            'controller_name' => 'LeCaillouxController',
        ]);
    }

    /**
     * Display items filtered by category.
     *
     * @param LeCaillouxRepository $lecaillouxRepository Repository for fetching items
     * @param string $category Category to filter items by
     */
    #[Route('/{category}', name: 'category')]
    public function filterByCategory(LeCaillouxRepository $lecaillouxRepository, string $category): Response
    {
        // Fetch items matching the given category
        $items = $lecaillouxRepository->findByCategory($category);

        // Render the template corresponding to the category, passing the items
        return $this->render("le_cailloux/{$category}.html.twig", [
            'items' => $items,
        ]);
    }
}