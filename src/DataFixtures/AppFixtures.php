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
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $auteurs = [];
        $tags = [];
        $lescailloux = [];

        // 📚 Création de 5 auteurs
        $noms = ['Martin', 'Dubois', 'Lemoine', 'Gauthier', 'Morel'];
        $prenoms = ['Alice', 'Jean', 'Sophie', 'Lucas', 'Claire'];

        for ($i = 0; $i < 5; $i++) {
            $auteur = new Auteurs();
            $auteur->setSurname($noms[$i]);
            $auteur->setFirstname($prenoms[$i]);
            $auteur->setBirthdate($faker->dateTimeBetween('-70 years', '-30 years'));

            $manager->persist($auteur);
            $auteurs[] = $auteur;
        }

        // 📖 Création de 15 livres
        $publishingHouses = ['Hachette', 'Gallimard', 'Flammarion', 'Albin Michel', 'Seuil'];

        for ($i = 0; $i < 15; $i++) {
            $book = new Livres();
            $book->setTitle($faker->sentence(3));
            $book->setPublicationdate($faker->dateTimeBetween('-50 years', 'now'));
            $book->setPublishinghouse($publishingHouses[array_rand($publishingHouses)]);
            $book->setAuteur($auteurs[array_rand($auteurs)]);

            $manager->persist($book);
        }

        // 🏷️ Création de 7 tags
        $tagNames = ['Fantaisie', 'Science-fiction', 'Horreur', 'Aventure', 'Historique', 'Philosophie', 'Biographie'];

        foreach ($tagNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // 🔖 Création de 25 bookmarks
        for ($i = 0; $i < 25; $i++) {
            $bookmark = new Bookmark();
            $bookmark->setUrl($faker->url);
            $bookmark->setComment($faker->sentence());

            shuffle($tags);
            $selectedTags = array_slice($tags, 0, mt_rand(1, 3));
            foreach ($selectedTags as $tag) {
                $bookmark->addTag($tag);
            }
            $manager->persist($bookmark);
        }

        // 🪨 Création de 15
        $fauneNames = ['Cagou', 'Gecko', 'Cerf', 'Cochon', 'Renard', 'Serpent', 'Chien'];
        $floreNames = ['Eucalyptus', 'Kaori', 'Kapok', 'Niaouli', 'Bananier', 'Sapin'];

        for ($i = 0; $i < 15; $i++) {
            $lecailloux = new LeCailloux();

            // Déterminer la catégorie
            $category = (mt_rand(0, 1) === 0) ? "faune" : "flore";
            $lecailloux->setCategory($category);

            // Attribuer un nom en fonction de la catégorie
            if ($category === "faune") {
                $lecailloux->setName($fauneNames[array_rand($fauneNames)] . ' ' . $i);
            } else {
                $lecailloux->setName($floreNames[array_rand($floreNames)] . ' ' . $i);
            }

            $manager->persist($lecailloux);
            $lescailloux[] = $lecailloux;
        }


        // 📷 Création de 70 médias
        $mediaDescriptions = [
            "Un magnifique spécimen découvert récemment.",
            "Un artefact ancien fascinant.",
            "Un élément naturel au design unique.",
            "Une pierre précieuse d'une beauté rare.",
            "Un minéral aux propriétés étonnantes."
        ];

        $mediaUrls = [
            "assets/images/media1.png",
            "assets/images/media2.png",
            "assets/images/media3.png",
            "assets/images/media4.png",
            "assets/images/media5.png"
        ];

        for ($i = 0; $i < 70; $i++) {
            $media = new Media();
            $media->setName('Pierre ' . $i);
            $media->setDescription($mediaDescriptions[array_rand($mediaDescriptions)]);
            $media->setLecailloux($lescailloux[array_rand($lescailloux)]);
            $media->setUrl($mediaUrls[array_rand($mediaUrls)]);

            $manager->persist($media);
        }

        $manager->flush();
    }
}
