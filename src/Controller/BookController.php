<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\BookType;
use App\Repository\LivresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller responsible for managing book-related actions.
 */
#[Route('/book', name: 'book_')]
final class BookController extends AbstractController
{
    /**
     * Display a list of all books.
     */
    #[Route('/', name: 'list')]
    public function index(LivresRepository $bookRepository): Response
    {
        // Retrieve all books from the repository
        $books = $bookRepository->findAll();
        // Count the total number of books
        $numberOfBooks = count($books);

        // Render the index template with books and their count
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'numberOfBooks' => $numberOfBooks,
        ]);
    }

    /**
     * Show details for a single book by its ID.
     */
    #[Route("/{id}", name: "show", requirements: ["id" => "\d+"])]
    public function showBook(int $id, LivresRepository $bookRepository): Response
    {
        // Attempt to find the book by ID
        $book = $bookRepository->find($id);

        // Throw a 404 error if the book does not exist
        if (!$book) {
            throw $this->createNotFoundException("No book found with ID " . $id);
        }

        // Render the show template for the retrieved book
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    /**
     * Filter books by the first letter of their title.
     */
    #[Route('/findByFirstLetter-{firstLetter}', name: 'findByFirstLetter')]
    public function findByFirstLetter(LivresRepository $bookRepository, string $firstLetter = null): Response
    {
        // If a first letter is provided, fetch matching books; otherwise fetch all
        $books = $firstLetter
            ? $bookRepository->findByFirstLetter($firstLetter)
            : $bookRepository->findAll();
        // Count how many books were found
        $numberOfBooks = count($books);

        // Render the index template with filtered books and the selected letter
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'selectedLetter' => $firstLetter,
            'numberOfBooks' => $numberOfBooks,
        ]);
    }

    /**
     * Handle creation of a new book.
     */
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Instantiate a new book entity
        $book = new Livres();
        // Create and process the form for a new book
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        // On successful form submission, save the new book and redirect
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Book created successfully!');
            return $this->redirectToRoute('book_list');
        }

        // Render the form template for creating a book
        return $this->render('book/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Handle editing of an existing book.
     */
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, LivresRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        // Retrieve the book to edit by ID
        $book = $bookRepository->find($id);

        // Throw a 404 error if the book is not found
        if (!$book) {
            throw $this->createNotFoundException("Book not found.");
        }

        // Create and process the form for editing the book
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        // On successful form submission, update the book and redirect
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Book successfully updated!');
            return $this->redirectToRoute('book_list');
        }

        // Render the form template for editing a book
        return $this->render('book/manage.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }
}