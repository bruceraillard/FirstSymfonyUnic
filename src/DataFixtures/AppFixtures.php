<?php

namespace App\DataFixtures;

use App\Entity\Auteurs;
use App\Entity\Bookmark;
use App\Entity\LeCailloux;
use App\Entity\Livres;
use App\Entity\Media;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $auteurs = [];
        $tags = [];
        $lescailloux = [];

        // Création de 5 auteurs
        for ($i = 1; $i <= 5; $i++) {
            $auteur = new Auteurs();
            $auteur->setSurname("NomAuteur $i");
            $auteur->setFirstname("PrénomAuteur $i");
            $auteur->setBirthdate(new \DateTime(mt_rand(1950, 2000) . '-'.mt_rand(1,12).'-'.mt_rand(1,28)));

            $manager->persist($auteur);
            $auteurs[] = $auteur; // Stocke les auteurs pour les attribuer aux livres
        }

        // Création de 15 livres
        for ($i = 0; $i < 15; $i++) {
            $book = new Livres();
            $book->setTitle('Livre ' .$i);
            $book->setPublicationdate(new \DateTime(mt_rand(1975, 2020)."-".mt_rand(1, 12)."-".mt_rand(1, 28)));
            $book->setPublishinghouse('Hachette');
            $book->setAuteur($auteurs[array_rand($auteurs)]);
            $manager->persist($book);
        }

        // Création de 5 tags
        $tagNames = ['Fantaisie', 'Science-fiction', 'Horreur', 'Aventure', 'Historique'];
        foreach ($tagNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // Création de 25 bookmark
        for ($i = 0; $i < 25; $i++) {
            $bookmark = new Bookmark();
            $bookmark->setUrl('https://getbootstrap.com/'.$i);
            $bookmark->setComment('Bruce');

            shuffle($tags);
            $selectedTags = array_slice($tags, 0, mt_rand(1, 3));
            foreach ($selectedTags as $tag) {
                $bookmark->addTag($tag);
            }
            $manager->persist($bookmark);
        }

        // Création de 25 lecailloux
        for ($i = 0; $i < 25; $i++) {
            $lecailloux = new LeCailloux();
            $lecailloux->setName('Chien'.$i);

            $lecaillouxcategory = ["faune", "flore"];
            $lecailloux->setCategory($lecaillouxcategory[array_rand($lecaillouxcategory)]);

            $manager->persist($lecailloux);
            $lescailloux[] = $lecailloux;
        }

        // Création de 70 media
        for ($i = 0; $i < 70; $i++) {
            $media = new Media();
            $media->setName('Pierre'.$i);
            $media->setDescription('Description '.$i);
            $media->setLecailloux($lescailloux[array_rand($lescailloux)]);

            $mediaurl = ["assets/images/media1.png", "assets/images/media2.png", "assets/images/media3.png", "assets/images/media4.png", "assets/images/media5.png"];
            shuffle($mediaurl);
            $selectedMedia = array_slice($mediaurl, 0, mt_rand(1, 5));
            foreach ($selectedMedia as $url) {
                $media->setUrl($url);
            }

            $manager->persist($media);
        }

        $manager->flush();
    }
}
