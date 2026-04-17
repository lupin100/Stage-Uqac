<?php

namespace App\Controller\Admin;

use App\Entity\Contributor;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContributorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contributor::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Contributeur')
            ->setEntityLabelInPlural('Contributeurs')
            ->setPageTitle(Crud::PAGE_INDEX, 'Liste des contributeurs')
            ->setPageTitle(Crud::PAGE_NEW, 'Créer un contributeur');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('displayName', 'Nom d\'affichage'),
            AssociationField::new('person', 'Personne associée'),
            AssociationField::new('publications', 'Publications'),
            AssociationField::new('projects', 'Projets'),
        ];
    }
}
