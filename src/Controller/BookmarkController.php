<?php

namespace App\Controller;

use App\Entity\Bookmark;
use App\Repository\BookmarkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookmarkController extends AbstractController
{
    #[Route('/bookmarks', name: 'bookmark_list')]
    public function index(BookmarkRepository $bookmarkRepository): Response
    {
        $bookmarks = $bookmarkRepository->findAll();

        return $this->render('bookmark/index.html.twig', [
            'bookmarks' => $bookmarks,
        ]);
    }

    #[Route('/bookmarks/add-sample', name: 'bookmark_add_sample')]
    public function addSampleBookmarks(EntityManagerInterface $entityManager): Response
    {
        $samples = [
            ['url' => 'https://www.symfony.com', 'comment' => 'Site officiel de Symfony'],
            ['url' => 'https://www.qwant.com/?l=fr', 'comment' => 'Moteur de recherche Qwant'],
            ['url' => 'https://getbootstrap.com/', 'comment' => 'Pour avoir le site le plus beau du monde']
        ];

        foreach ($samples as $data) {
            $bookmark = new Bookmark();
            $bookmark->setUrl($data['url']);
            $bookmark->setComment($data['comment']);
            $bookmark->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($bookmark);
        }

        $entityManager->flush();

        return new Response('<h3>✅ 3 marque-pages ont été ajoutés avec succès !</h3>');
    }
}

