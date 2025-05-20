<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use App\Entity\Auteurs;
use App\Entity\Bookmark;
use App\Entity\Employee;
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
        // Initialize Faker for French data
        $faker = Factory::create('fr_FR');

        // Collections to hold created entities for later associations
        $auteurs = [];
        $tags = [];
        $lescailloux = [];
        $adresses = [];

        //────────────────────────────────────────────────────────────────────────
        // Create 5 authors with random names and birthdates
        //────────────────────────────────────────────────────────────────────────
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

        //────────────────────────────────────────────────────────────────────────
        // Create 15 books with random titles, dates, publishers, and authors
        //────────────────────────────────────────────────────────────────────────
        $publishingHouses = ['Hachette', 'Gallimard', 'Flammarion', 'Albin Michel', 'Seuil'];

        for ($i = 0; $i < 15; $i++) {
            $book = new Livres();
            $book->setTitle($faker->sentence(3));
            $book->setPublicationdate($faker->dateTimeBetween('-50 years', 'now'));
            $book->setPublishinghouse($publishingHouses[array_rand($publishingHouses)]);
            $book->setAuteur($auteurs[array_rand($auteurs)]);
            $manager->persist($book);
        }

        //────────────────────────────────────────────────────────────────────────
        // Create a set of tags for categorizing bookmarks
        //────────────────────────────────────────────────────────────────────────
        $tagNames = ['Fantaisie', 'Science-fiction', 'Horreur', 'Aventure', 'Historique', 'Philosophie', 'Biographie'];

        foreach ($tagNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        //────────────────────────────────────────────────────────────────────────
        // Create 25 bookmarks with random URLs, comments, and tag assignments
        //────────────────────────────────────────────────────────────────────────
        for ($i = 0; $i < 25; $i++) {
            $bookmark = new Bookmark();
            $bookmark->setUrl($faker->url);
            $bookmark->setComment($faker->sentence());

            // Assign 1–3 random tags to each bookmark
            shuffle($tags);
            foreach (array_slice($tags, 0, rand(1, 3)) as $tag) {
                $bookmark->addTag($tag);
            }

            $manager->persist($bookmark);
        }

        //────────────────────────────────────────────────────────────────────────
        // Create 15 LeCailloux entries with random category and name
        //────────────────────────────────────────────────────────────────────────
        $fauneNames = ['Cagou', 'Gecko', 'Cerf', 'Cochon', 'Renard', 'Serpent', 'Chien'];
        $floreNames = ['Eucalyptus', 'Kaori', 'Kapok', 'Niaouli', 'Bananier', 'Sapin'];

        for ($i = 0; $i < 15; $i++) {
            $lecailloux = new LeCailloux();
            $category = (rand(0, 1) === 0) ? 'faune' : 'flore';
            $lecailloux->setCategory($category);
            $names = $category === 'faune' ? $fauneNames : $floreNames;
            $lecailloux->setName($names[array_rand($names)] . ' ' . $i);

            $manager->persist($lecailloux);
            $lescailloux[] = $lecailloux;
        }

        //────────────────────────────────────────────────────────────────────────
        // Create 70 media items linked to random LeCailloux entries
        //────────────────────────────────────────────────────────────────────────
        $mediaDescriptions = [
            'Un magnifique spécimen découvert récemment.',
            'Un artefact ancien fascinant.',
            'Un élément naturel au design unique.',
            'Une pierre précieuse d’une beauté rare.',
            'Un minéral aux propriétés étonnantes.',
        ];
        $mediaUrls = [
            'assets/images/media1.png',
            'assets/images/media2.png',
            'assets/images/media3.png',
            'assets/images/media4.png',
            'assets/images/media5.png',
        ];

        for ($i = 0; $i < 70; $i++) {
            $media = new Media();
            $media->setName('Pierre ' . $i);
            $media->setDescription($mediaDescriptions[array_rand($mediaDescriptions)]);
            $media->setUrl($mediaUrls[array_rand($mediaUrls)]);
            $media->setLecailloux($lescailloux[array_rand($lescailloux)]);

            $manager->persist($media);
        }

        //────────────────────────────────────────────────────────────────────────
        // Create 10 addresses with random data
        //────────────────────────────────────────────────────────────────────────
        for ($i = 0; $i < 10; $i++) {
            $adresse = new Adresse();
            $adresse->setAdresse($faker->streetAddress);
            $adresse->setPostalcode((int)$faker->postcode);
            $adresse->setCountry($faker->country);
            $manager->persist($adresse);
            $adresses[] = $adresse;
        }

        //────────────────────────────────────────────────────────────────────────
        // Create 10 employees and assign each 1–3 random addresses
        //────────────────────────────────────────────────────────────────────────
        for ($i = 0; $i < 10; $i++) {
            $employee = new Employee();
            $employee->setFirstname($faker->firstName);
            $employee->setSurname($faker->lastName);

            // Link random addresses to the employee
            shuffle($adresses);
            foreach (array_slice($adresses, 0, rand(1, 3)) as $adresse) {
                $employee->addAdress($adresse);
            }

            $manager->persist($employee);
        }

        // Persist all created entities to the database
        $manager->flush();
    }
}