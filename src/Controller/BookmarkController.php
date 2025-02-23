<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Define the controller for bookmarks.
// The base route is '/bookmarks' and all route names will be prefixed with 'bookmark_'.
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

        // Render the view 'bookmark/index.html.twig' and pass the 'bookmarks' variable.
        return $this->render('bookmark/index.html.twig', [
            'bookmarks' => $bookmarks,
        ]);
    }

    // Method to add sample bookmarks.
    // Accessible via the URL '/bookmarks/add-sample' and named 'bookmark_add_sample'.
    #[Route('/add-sample', name: 'add_sample')]
    public function addSampleBookmarks(EntityManagerInterface $entityManager): Response
    {
        // Define an array with sample data for bookmarks.
        $samples = [
            ['url' => 'https://www.symfony.com', 'comment' => 'Official Symfony website'],
            ['url' => 'https://www.qwant.com/?l=fr', 'comment' => 'Qwant search engine'],
            ['url' => 'https://getbootstrap.com/', 'comment' => 'For the most beautiful site in the world']
        ];

        // For each sample data set, create and configure a Bookmark object.
        foreach ($samples as $data) {
            $bookmark = new Bookmark();
            $bookmark->setUrl($data['url']);
            $bookmark->setComment($data['comment']);
            $bookmark->setCreatedAt(new \DateTimeImmutable());

            // Mark the object for insertion into the database.
            $entityManager->persist($bookmark);
        }

        // Execute all pending database operations.
        $entityManager->flush();

        // Return a simple response confirming that the bookmarks have been added.
        return new Response('<h3>✅ 3 bookmarks have been added successfully!</h3>');
    }

    // Method to display the details of a bookmark using its identifier.
    // Accessible via the URL '/bookmarks/{id}' where {id} must be numeric (thanks to the requirement).
    #[Route("/{id}", name: "show", requirements: ["id" => "\d+"])]
    public function showBookmark(int $id, BookmarkRepository $bookmarkRepository): Response
    {
        // Find the bookmark corresponding to the provided id in the database.
        $bookmark = $bookmarkRepository->find($id);

        // If no bookmark is found, throw a 404 exception.
        if (!$bookmark) {
            throw $this->createNotFoundException("No bookmark found with id " . $id);
        }

        // Render the view 'bookmark/show.html.twig' and pass the 'bookmark' variable.
        return $this->render('bookmark/show.html.twig', [
            'bookmark' => $bookmark,
        ]);
    }
}
