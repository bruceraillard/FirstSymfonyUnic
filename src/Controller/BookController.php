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

// Controller responsible for handling all book-related operations.
#[Route('/book', name: 'book_')]
final class BookController extends AbstractController
{
    // Display a list of all books.
    #[Route('/', name: 'list')]
    public function index(LivresRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
        $numberOfBooks = count($books);

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'numberOfBooks' => $numberOfBooks,
        ]);
    }

    // Display the details of a single book.
    #[Route("/{id}", name: "show", requirements: ["id" => "\d+"])]
    public function showBook(int $id, LivresRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException("No book found with ID " . $id);
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    // Filter books by the first letter of the title.
    #[Route('/findByFirstLetter-{firstLetter}', name: 'findByFirstLetter')]
    public function findByFirstLetter(LivresRepository $bookRepository, string $firstLetter = null): Response
    {
        $books = $firstLetter
            ? $bookRepository->findByFirstLetter($firstLetter)
            : $bookRepository->findAll();

        $numberOfBooks = count($books);

        return $this->render("book/index.html.twig", [
            'books' => $books,
            'selectedLetter' => $firstLetter,
            'numberOfBooks' => $numberOfBooks,
        ]);
    }

    // Create a new book.
    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Livres();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'Book created successfully!');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Edit an existing book.
    #[Route('/edit/{id}', name: 'edit')]
    public function edit(int $id, Request $request, LivresRepository $bookRepository, EntityManagerInterface $entityManager): Response
    {
        $book = $bookRepository->find($id);

        if (!$book) {
            throw $this->createNotFoundException("Book not found.");
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Book successfully updated!');
            return $this->redirectToRoute('book_list');
        }

        return $this->render('book/manage.html.twig', [
            'form' => $form->createView(),
            'book' => $book,
        ]);
    }
}
