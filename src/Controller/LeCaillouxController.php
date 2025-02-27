<?php

namespace App\Controller;

use App\Repository\LeCaillouxRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Define the controller for handling "Le Cailloux" related requests.
// The base route is '/lecailloux', and all route names will be prefixed with 'lecailloux_'.
#[Route('/lecailloux', name: 'lecailloux_')]
final class LeCaillouxController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('le_cailloux/index.html.twig', [
            'controller_name' => 'LeCaillouxController',
        ]);
    }
    // Method to display items based on a given category.
    // Accessible via the URL '/lecailloux/{category}' where {category} represents the requested category.
    #[Route('/{category}', name: 'category')]
    public function filterByCategory(LeCaillouxRepository $lecaillouxRepository, string $category): Response
    {
        // Retrieve items from the repository based on the specified category.
        $items = $lecaillouxRepository->findByCategory($category);

        // Render the corresponding template for the given category.
        return $this->render("le_cailloux/{$category}.html.twig", [
            'items' => $items,
        ]);
    }
}
