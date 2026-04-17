<?php

namespace App\Controller\Admin;

use App\Entity\Institution;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class InstitutionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Institution::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Institution')
            ->setEntityLabelInPlural('Institutions')
            ->setDefaultSort(['name' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),            
            TextField::new('name', 'Nom de l\'institution'),            
            UrlField::new('url', 'Site Web'),
            AssociationField::new('departement', 'Département rattaché'),
            AssociationField::new('persons', 'Personnes associées'),
        ];
    }
}