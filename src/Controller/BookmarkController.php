<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Form\BookmarkType;
use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Define the controller for handling bookmarks.
// The base route is '/bookmarks', and all route names will be prefixed with 'bookmark_'.
#[Route('/bookmarks', name: 'bookmark_')]
class BookmarkController extends AbstractController
{
    // Method to list all bookmarks.
    // Accessible via the URL '/bookmarks/' and named 'bookmark_list'.
    #[Route('/', name: 'list')]
    public function index(BookmarkRepository $bookmarkRepository): Response
    {
        // Retrieve all bookmarks from the repository.
        $bookmarks = $bookmarkRepository->findAll();

        // Render the 'bookmark/index.html.twig' view and pass the 'bookmarks' variable.
        return $this->render('bookmark/index.html.twig', [
            'bookmarks' => $bookmarks,
        ]);
    }

    // Method to display the details of a bookmark using its identifier.
    // Accessible via the URL '/bookmarks/{id}', where {id} must be numeric (enforced by the requirement).
    #[Route("/{id}", name: "show", requirements: ["id" => "\d+"])]
    public function showBookmark(int $id, BookmarkRepository $bookmarkRepository): Response
    {
        // Find the bookmark corresponding to the given ID in the database.
        $bookmark = $bookmarkRepository->find($id);

        // If no bookmark is found, throw a 404 exception.
        if (!$bookmark) {
            throw $this->createNotFoundException("No bookmark found with ID " . $id);
        }

        // Render the 'bookmark/show.html.twig' view and pass the 'bookmark' variable.
        return $this->render('bookmark/show.html.twig', [
            'bookmark' => $bookmark,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $bookmark = new Bookmark();
        $bookmark->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bookmark);
            $em->flush();

            $this->addFlash('success', 'Bookmark créé avec succès !');

            return $this->redirectToRoute('bookmark_list');
        }

        return $this->render('bookmark/manage.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Bookmark $bookmark, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Bookmark mis à jour !');

            return $this->redirectToRoute('bookmark_list');
        }

        return $this->render('bookmark/manage.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
        ]);
    }
} 