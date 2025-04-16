<?php

namespace App\Controller;

use App\Entity\Auteurs;
use App\Form\AuteurType;
use App\Form\AuthorType;
use App\Repository\AuteursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Defines a controller for handling author-related requests.
// The base route is '/author', and all route names will be prefixed with 'author_'.
#[Route('/author', name: 'author_')]
final class AuthorController extends AbstractController
{
    // Handles the listing of all authors.
    #[Route('/', name: 'list')]
    public function index(AuteursRepository $auteursRepository): Response
    {
        $auteurs = $auteursRepository->findAll();
        $numberOfAuthors = count($auteurs);

        return $this->render('author/index.html.twig', [
            'authors' => $auteurs,
            'numberOfAuthors' => $numberOfAuthors,
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, AuteursRepository $auteursRepository): Response
    {
        $auteur = $auteursRepository->find($id);

        if (!$auteur) {
            throw $this->createNotFoundException("Auteur non trouvé avec l'ID " . $id);
        }

        return $this->render('author/show.html.twig', [
            'author' => $auteur,
        ]);
    }

    // Filters authors who have written more than one book.
    #[Route('/multi-book', name: 'multi_book')]
    public function findMultiBookAuthors(AuteursRepository $auteursRepository): Response
    {
        $auteurs = $auteursRepository->findMultiBookAuthor();
        $numberOfAuthors = count($auteurs);

        return $this->render('author/index.html.twig', [
            'authors' => $auteurs,
            'numberOfAuthors' => $numberOfAuthors,
            'filter' => 'multi_book',
        ]);
    }

    // Creates a new author.
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auteur = new Auteurs();
        $form = $this->createForm(AuthorType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auteur);
            $entityManager->flush();

            $this->addFlash('success', 'Auteur créé avec succès !');
            return $this->redirectToRoute('author_list');
        }

        return $this->render('author/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Edits an existing author.
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, AuteursRepository $auteursRepository, EntityManagerInterface $entityManager): Response
    {
        $auteur = $auteursRepository->find($id);

        if (!$auteur) {
            throw $this->createNotFoundException("Auteur non trouvé.");
        }

        $form = $this->createForm(AuthorType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Auteur mis à jour avec succès !');
            return $this->redirectToRoute('author_list');
        }

        return $this->render('author/manage.html.twig', [
            'form' => $form->createView(),
            'author' => $auteur,
        ]);
    }
} 