<?php

namespace App\Controller\Admin;

use App\Entity\Person;
use App\Enum\PersonEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class PersonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Person::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Personne')
            ->setEntityLabelInPlural('Personnes')
            ->setDefaultSort(['lastName' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $roleChoices = [];
        foreach (PersonEnum::cases() as $case) {
            $roleChoices[$case->value] = $case;
        }

        return [
            IdField::new('id')->hideOnForm(),

            // --- Informations de base ---
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            EmailField::new('email', 'Adresse Email'),
            TextField::new('jobTitle', 'Poste / Titre')
                ->setRequired(false),

            // --- Rôle et Statut ---
            ChoiceField::new('role', 'Rôle principal')
                ->setChoices($roleChoices)
                ->formatValue(fn ($value) => $value?->value),
            BooleanField::new('isActive', 'Profil Actif'),

            // --- Médias et Liens ---
            ImageField::new('photoPath', 'Photo de profil')
                ->setUploadDir('public/uploads/persons')
                ->setBasePath('/uploads/persons')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false),

            UrlField::new('personalPageUrl', 'Page Web Personnelle')
                ->setRequired(false)
                ->hideOnIndex(), // Masqué dans la liste pour ne pas surcharger

            TextEditorField::new('biography', 'Biographie')
                ->hideOnIndex(),

            // --- Relations ---
            AssociationField::new('departements', 'Départements associés')
                ->hideOnIndex(),
            AssociationField::new('institutions', 'Institutions associées')
                ->hideOnIndex(),
            AssociationField::new('studentProfile', 'Profil Étudiant')
                ->hideOnIndex(),
            AssociationField::new('contributor', 'Profil Contributeur')
                ->hideOnIndex(),

            // --- Timestamps (Gérés par le constructeur et PreUpdate) ---
            // On les cache dans les formulaires de création/édition car l'entité s'en occupe toute seule
            DateTimeField::new('createdAt', 'Créé le')
                ->hideOnForm(),
            DateTimeField::new('updatedAt', 'Dernière modification')
                ->onlyOnDetail(), // Visible uniquement sur la page de détail complet
        ];
    }
}