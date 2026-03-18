<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\News;
use App\Entity\Publication;
use App\Enum\EventEnum;
use App\Enum\PublicationEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Init Faker french
        $faker = Factory::create('fr_FR');

        $itSubjects = ['l\'IA', 'le Machine Learning', 'la Cybersécurité', 'l\'Algorithmique Avancée', 'le Big Data', 'l\'Informatique Quantique', 'les Réseaux de Neurones', 'la Réalité Virtuelle', 'le Cloud Computing', 'l\'IoT'];
        
        $eventPrefixes = ['Conférence sur', 'Séminaire :', 'Atelier de recherche :', 'Soutenance de thèse :', 'Symposium sur'];
        $newsPrefixes = ['Nouvelle avancée pour', 'Découverte majeure en', 'Notre laboratoire prime', 'Lancement du projet sur', 'Partenariat stratégique en'];
        $pubPrefixes = ['Étude comparative sur', 'Nouvelle approche de', 'Analyse des performances pour', 'Modélisation de'];

        // --- Event ---
        $eventEnums = EventEnum::cases();
        for ($i = 0; $i < 30; $i++) {
            $event = new Event();
            
            $startDate = \DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', '+6 months'));
            $endDate = $startDate->modify('+' . $faker->numberBetween(1, 5) . ' hours');

            // Titre généré : Ex: "Séminaire : la Cybersécurité"
            $title = $faker->randomElement($eventPrefixes) . ' ' . $faker->randomElement($itSubjects);

            $event->setTitle($title)
                  ->setContent($faker->realText(800))
                  ->setStartDate($startDate)
                  ->setEndDate($endDate)
                  ->setEventType($faker->randomElement($eventEnums));
            
            $manager->persist($event);
        }

        // --- News ---
        for ($i = 0; $i < 30; $i++) {
            $news = new News();

            // Titre généré : Ex: "Nouvelle avancée pour le Machine Learning"
            $title = $faker->randomElement($newsPrefixes) . ' ' . $faker->randomElement($itSubjects);
            
            $news->setTitle($title)
                 ->setContent($faker->realText(800))
                 ->setPublishedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 year', 'now')))
                 ->setImagePath($faker->imageUrl(800, 600, 'technics', true));
            
            $manager->persist($news);
        }

        // --- Publication ---
        $publicationEnums = PublicationEnum::cases();
        for ($i = 0; $i < 20; $i++) {
            $publication = new Publication();

            // Titre généré : Ex: "Étude comparative sur les Réseaux de Neurones"
            $title = $faker->randomElement($pubPrefixes) . ' ' . $faker->randomElement($itSubjects);
            
            $publication->setTitle($title)
                        ->setYear($faker->numberBetween(2016, 2024))
                        ->setExternalUrl($faker->optional(0.7)->url()) 
                        ->setPublicationType($faker->randomElement($publicationEnums));
            
            $manager->persist($publication);
        }

        $manager->flush();
    }
}
