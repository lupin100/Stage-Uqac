<?php

namespace App\DataFixtures;

use App\Entity\Contributor;
use App\Entity\Departement;
use App\Entity\Event;
use App\Entity\Institution;
use App\Entity\News;
use App\Entity\Person;
use App\Entity\Project;
use App\Entity\Publication;
use App\Entity\StudentDegree;
use App\Entity\StudentProfile;
use App\Entity\User;
use App\Enum\EventEnum;
use App\Enum\PersonEnum;
use App\Enum\ProjectEnum;
use App\Enum\PublicationEnum;
use App\Enum\StudentDegreeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Init Faker french
        $faker = Factory::create('fr_FR');

        // --- Administrateur ---
        $admin = new User();
        $admin->setEmail('admin@uqac.ca');
        $admin->setRoles(['ROLE_ADMIN']);
        // Hachage du mot de passe "admin123"
        $password = $this->hasher->hashPassword($admin, 'admin123');
        $admin->setPassword($password);
        $manager->persist($admin);

        // --- Données de génération ---
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

            $title = $faker->randomElement($eventPrefixes) . ' ' . $faker->randomElement($itSubjects);

            $event->setTitle($title)
                  ->setContent($faker->realText(2000))
                  ->setStartDate($startDate)
                  ->setEndDate($endDate)
                  ->setEventType($faker->randomElement($eventEnums));

            $manager->persist($event);
        }

        // --- News ---
        for ($i = 0; $i < 30; $i++) {
            $news = new News();

            $title = $faker->randomElement($newsPrefixes) . ' ' . $faker->randomElement($itSubjects);

            $news->setTitle($title)
                 ->setContent($faker->realText(2000))
                 ->setPublishedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-2 year', 'now')))
                 ->setImagePath('https://loremflickr.com/800/600/computer,technology?lock=' . $i);

            $manager->persist($news);
        }

        // --- Departements ---
        $departements = [];
        for ($i = 0; $i < 10; $i++) {
            $dep = new Departement();
            $dep->setName('Département ' . $faker->word());
            $dep->setUrl($faker->optional(0.7)->url());

            $manager->persist($dep);
            $departements[] = $dep;
        }

        // --- Institutions ---
        $institutions = [];
        for ($i = 0; $i < 10; $i++) {
            $inst = new Institution();
            $inst->setName('Université ' . $faker->city());
            $inst->setUrl($faker->optional(0.8)->url());
            $inst->setDepartement($faker->randomElement($departements));

            $manager->persist($inst);
            $institutions[] = $inst;
        }

        // --- Projects ---
        $projectEnums = ProjectEnum::cases();
        for ($i = 0; $i < 15; $i++) {
            $project = new Project();
            $project->setTitle('Projet : ' . $faker->randomElement($itSubjects));
            $project->setSummary($faker->realText(800));
            $project->setFundingSource($faker->company());
            $project->setIsFinished($faker->boolean(40));
            $project->setThematic($faker->randomElement($projectEnums));

            $manager->persist($project);
        }

        // --- StudentDegrees ---
        $degreeEnums = StudentDegreeEnum::cases();
        $studentDegrees = [];
        for ($i = 0; $i < 20; $i++) {
            $degree = new StudentDegree();
            $startYear = $faker->numberBetween(2018, 2023);

            $degree->setStartYear($startYear);
            $degree->setEndYear($startYear + $faker->numberBetween(1, 3));
            $degree->setDegree($faker->randomElement($degreeEnums));

            $manager->persist($degree);
            $studentDegrees[] = $degree;
        }

        // --- StudentProfiles ---
        $studentProfiles = [];
        for ($i = 0; $i < 40; $i++) {
            $profile = new StudentProfile();
            $profile->setSupervisor($faker->name());
            $profile->setCoSupervisor($faker->boolean(30) ? $faker->name() : null);
            $profile->setStudentDegree($faker->randomElement($studentDegrees));
            $profile->setTopic('Recherche sur ' . $faker->catchPhrase());

            $manager->persist($profile);
            $studentProfiles[] = $profile;
        }

        // --- Persons ---
        $personEnums = PersonEnum::cases();
        $availableDepartements = $departements;
        $availableInstitutions = $institutions;
        $availableProfiles = $studentProfiles;
        $persons = [];

        for ($i = 0; $i < 150; $i++) {
            $person = new Person();
            $person->setFirstName($faker->firstName());
            $person->setLastName($faker->lastName());
            $person->setEmail($faker->unique()->safeEmail());
            $person->setBiography($faker->realText(1000));
            $person->setIsActive($faker->boolean(80));
            $person->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years', '-1 year')));
            $person->setUpdatedAt(new \DateTimeImmutable());
            $person->setJobTitle($faker->jobTitle());
            $person->setPersonalPageUrl($faker->optional(0.6)->url());
            $person->setRole($faker->randomElement($personEnums));

            if (!empty($availableDepartements) && $faker->boolean(30)) {
                $person->setDepartement(array_pop($availableDepartements));
            }
            if (!empty($availableInstitutions) && $faker->boolean(30)) {
                $person->setInstitution(array_pop($availableInstitutions));
            }
            if (!empty($availableProfiles) && $faker->boolean(50)) {
                $person->setStudentProfile(array_pop($availableProfiles));
            }

            $manager->persist($person);
            $persons[] = $person;
        }

        // --- Contributors ---
        $contributors = [];
        $availablePersons = $persons;
        shuffle($availablePersons);

        for ($i = 0; $i < 20; $i++) {
            $contributor = new Contributor();
            $contributor->setDisplayName($faker->name());

            if (!empty($availablePersons)) {
                $linkedPerson = array_pop($availablePersons);
                $contributor->setPerson($linkedPerson);
                $contributor->setDisplayName($linkedPerson->getFirstName() . ' ' . $linkedPerson->getLastName());
            }

            $manager->persist($contributor);
            $contributors[] = $contributor;
        }

        // --- Publication ---
        $publicationEnums = PublicationEnum::cases();
        for ($i = 0; $i < 20; $i++) {
            $publication = new Publication();
            $title = $faker->randomElement($pubPrefixes) . ' ' . $faker->randomElement($itSubjects);

            $publication->setTitle($title)
                        ->setYear($faker->numberBetween(2016, 2024))
                        ->setExternalUrl($faker->optional(0.7)->url())
                        ->setPublicationType($faker->randomElement($publicationEnums))
                        ->setContributor($faker->randomElement($contributors));

            $manager->persist($publication);
        }

        $manager->flush();
    }
}
