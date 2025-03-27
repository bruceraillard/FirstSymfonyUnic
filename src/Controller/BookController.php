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

// Defines a controller for handling book-related requests.
// The base route is '/book', and all route names will be prefixed with 'book_'.
#[Route('/book', name: 'book_')]
final class BookController extends AbstractController
{
    // Handles the listing of all books.
    // Accessible via the URL '/book/' and named 'book_list'.
    #[Route('/', name: 'list')]
    public function index(LivresRepository $bookRepository): Response
    {
        // Retrieve all books from the repository.
        $books = $bookRepository->findAll();

        // Count the number of books retrieved.
        $numberOfBooks = count($books);

        // Render the 'book/index.html.twig' view and pass the 'books' variable along with the total count.
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'numberOfBooks' => $numberOfBooks,
        ]);
    }

    // Handles the display of a specific book's details.
    // Accessible via the URL '/book/{id}', where {id} must be numeric (as enforced by the requirement).
    #[Route("/{id}", name: "show", requirements: ["id" => "\d+"])]
    public function showBook(int $id, LivresRepository $bookRepository): Response
    {
        // Find the book corresponding to the provided ID in the database.
        $book = $bookRepository->find($id);

        // If no book is found, throw a 404 exception.
        if (!$book) {
            throw $this->createNotFoundException("No book found with ID " . $id);
        }

        // Render the 'book/show.html.twig' view and pass the 'book' variable.
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    // Handles filtering books based on the first letter of their title.
    // Accessible via the URL '/book/findByFirstLetter-{firstLetter}'.
    #[Route('/findByFirstLetter-{firstLetter}', name: 'findByFirstLetter')]
    public function findByFirstLetter(LivresRepository $bookRepository, string $firstLetter = null): Response
    {
        // If a first letter is provided, filter books by that letter.
        if ($firstLetter) {
            $books = $bookRepository->findByFirstLetter($firstLetter);
        } else {
            // If no letter is provided, retrieve all books.
            $books = $bookRepository->findAll();
        }

        // Count the number of books retrieved after filtering.
        $numberOfBooks = count($books);

        // Render the 'book/index.html.twig' view with the filtered books and the selected letter.
        return $this->render("book/index.html.twig", [
            'books' => $books,
            'selectedLetter' => $firstLetter,
            'numberOfBooks' => $numberOfBooks,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Create a new instance of Livres
        $livre = new Livres();

        // Create the form based on BookType and bind it to the Livres entity
        $form = $this->createForm(BookType::class, $livre);

        // Handle the HTTP request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the entity to the database
            $entityManager->persist($livre);
            $entityManager->flush();

            // Add a flash message
            $this->addFlash('success', 'Book created successfully!');

            // Redirect to the list of books (route 'book_list')
            return $this->redirectToRoute('book_list');
        }

        // Render the form in the template
        return $this->render('book/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Defines the route for editing a book, e.g. /book/edit/1
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, LivresRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        // Fetch the book entity by its ID
        $livre = $bookRepository->find($id);

        // If the book doesn't exist, throw a 404 error
        if (!$livre) {
            throw $this->createNotFoundException("Book not found.");
        }

        // Create the form and bind it to the existing book entity
        $form = $this->createForm(BookType::class, $livre);

        // Handle the request (submit, validation, etc.)
        $form->handleRequest($request);

        // If the form is submitted and valid, save the changes to the database
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // No need to call persist, entity is already managed

            // Add a success flash message
            $this->addFlash('success', 'Book successfully updated!');

            // Redirect to the book list page after saving
            return $this->redirectToRoute('book_list');
        }

        // Render the same template used for creation, passing the form view
        return $this->render('book/manage.html.twig', [
            'form' => $form->createView(),
            'book' => $livre,
        ]);
    }

}
