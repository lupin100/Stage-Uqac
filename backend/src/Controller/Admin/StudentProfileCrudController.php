<?php

namespace App\Controller\Admin;

use App\Entity\StudentProfile;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StudentProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StudentProfile::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Profil Étudiant')
            ->setEntityLabelInPlural('Profils Étudiants');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('person', 'Étudiant')
                ->setRequired(true),
            AssociationField::new('studentDegree', 'Diplôme ou Cursus')
                ->setRequired(true),
            TextField::new('topic', 'Sujet de recherche / d\'étude')
                ->setRequired(false),
            AssociationField::new('supervisor', 'Directeur/rice de recherche')
                ->setRequired(false),
            AssociationField::new('coSupervisor', 'Co-directeur/rice')
                ->setRequired(false),
        ];
    }
}