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

/**
 * Controller for managing bookmarks.
 */
#[Route('/bookmarks', name: 'bookmark_')]
class BookmarkController extends AbstractController
{
    /**
     * Display a list of all bookmarks.
     */
    #[Route('/', name: 'list')]
    public function index(BookmarkRepository $bookmarkRepository): Response
    {
        // Retrieve all bookmark records from the database
        $bookmarks = $bookmarkRepository->findAll();

        // Render the list view, passing the bookmarks collection
        return $this->render('bookmark/index.html.twig', [
            'bookmarks' => $bookmarks,
        ]);
    }

    /**
     * Show details for a single bookmark by its ID.
     */
    #[Route("/{id}", name: "show", requirements: ["id" => "\d+"])]
    public function showBookmark(int $id, BookmarkRepository $bookmarkRepository): Response
    {
        // Look up the bookmark with the specified ID
        $bookmark = $bookmarkRepository->find($id);

        // Throw a 404 error if the bookmark does not exist
        if (!$bookmark) {
            throw $this->createNotFoundException("No bookmark found with ID " . $id);
        }

        // Render the detail view for the found bookmark
        return $this->render('bookmark/show.html.twig', [
            'bookmark' => $bookmark,
        ]);
    }

    /**
     * Handle creation of a new bookmark.
     */
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // Initialize a new Bookmark entity and set its creation timestamp
        $bookmark = new Bookmark();
        $bookmark->setCreatedAt(new \DateTimeImmutable());

        // Build and process the creation form
        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->handleRequest($request);

        // On valid submission, persist the new bookmark and redirect to list
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bookmark);
            $em->flush();

            $this->addFlash('success', 'Bookmark créé avec succès !');

            return $this->redirectToRoute('bookmark_list');
        }

        // Render the form for creating a bookmark
        return $this->render('bookmark/manage.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    /**
     * Handle editing of an existing bookmark.
     */
    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, Bookmark $bookmark, EntityManagerInterface $em): Response
    {
        // Build and process the edit form for the provided Bookmark entity
        $form = $this->createForm(BookmarkType::class, $bookmark);
        $form->handleRequest($request);

        // On valid submission, update the bookmark and redirect to list
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Bookmark mis à jour !');

            return $this->redirectToRoute('bookmark_list');
        }

        // Render the form for editing a bookmark
        return $this->render('bookmark/manage.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
        ]);
    }
}