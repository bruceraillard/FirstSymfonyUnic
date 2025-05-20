<?php

namespace App\Controller;

use App\Entity\Auteurs;
use App\Form\AuthorType;
use App\Repository\AuteursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/author', name: 'author_')]
final class AuthorController extends AbstractController
{
    /**
     * Display a list of all authors.
     */
    #[Route('/', name: 'list')]
    public function index(AuteursRepository $auteursRepository): Response
    {
        // Fetch all authors from the database
        $auteurs = $auteursRepository->findAll();
        // Count total number of authors retrieved
        $numberOfAuthors = count($auteurs);

        // Render the list view with authors and their count
        return $this->render('author/index.html.twig', [
            'authors' => $auteurs,
            'numberOfAuthors' => $numberOfAuthors,
        ]);
    }

    /**
     * Show details for a single author by ID.
     */
    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, AuteursRepository $auteursRepository): Response
    {
        // Attempt to find the author with the given ID
        $auteur = $auteursRepository->find($id);

        // If not found, throw a 404 error
        if (!$auteur) {
            throw $this->createNotFoundException("Author not found with ID " . $id);
        }

        // Render the detail view for the found author
        return $this->render('author/show.html.twig', [
            'author' => $auteur,
        ]);
    }

    /**
     * List authors who have written more than one book.
     */
    #[Route('/multi-book', name: 'multi_book')]
    public function findMultiBookAuthors(AuteursRepository $auteursRepository): Response
    {
        // Retrieve authors with multiple books
        $auteurs = $auteursRepository->findMultiBookAuthor();
        // Count how many such authors exist
        $numberOfAuthors = count($auteurs);

        // Render the view for authors with multiple books
        return $this->render('author/multibookAuthor.html.twig', [
            'authors' => $auteurs,
            'numberOfAuthors' => $numberOfAuthors,
            'filter' => 'multi_book',
        ]);
    }

    /**
     * Handle creation of a new author.
     */
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Initialize a new author entity
        $auteur = new Auteurs();

        // Create and process the author form
        $form = $this->createForm(AuthorType::class, $auteur);
        $form->handleRequest($request);

        // On valid submission, save the author and redirect
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auteur);
            $entityManager->flush();

            $this->addFlash('success', 'Author created successfully!');
            return $this->redirectToRoute('author_list');
        }

        // Render the form view for creating a new author
        return $this->render('author/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Handle editing of an existing author.
     */
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, AuteursRepository $auteursRepository, EntityManagerInterface $entityManager): Response
    {
        // Find the author to edit by ID
        $auteur = $auteursRepository->find($id);

        // If not found, throw a 404 error
        if (!$auteur) {
            throw $this->createNotFoundException("Author not found.");
        }

        // Create and process the author edit form
        $form = $this->createForm(AuthorType::class, $auteur);
        $form->handleRequest($request);

        // On valid submission, update the author and redirect
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Author updated successfully!');
            return $this->redirectToRoute('author_list');
        }

        // Render the form view for editing an author
        return $this->render('author/manage.html.twig', [
            'form' => $form->createView(),
            'author' => $auteur,
        ]);
    }
}