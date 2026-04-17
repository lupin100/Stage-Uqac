<?php

namespace App\Controller\Admin;

use App\Entity\StudentDegree;
use App\Enum\StudentDegreeEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class StudentDegreeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StudentDegree::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Diplôme')
            ->setEntityLabelInPlural('Diplômes')
            ->setDefaultSort(['startYear' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $degreeChoices = [];
        foreach (StudentDegreeEnum::cases() as $case) {
            $degreeChoices[$case->value] = $case;
        }

        return [
            IdField::new('id')->hideOnForm(),            
            ChoiceField::new('degree', 'Niveau du diplôme')
                ->setChoices($degreeChoices)
                ->formatValue(fn ($value) => $value?->value),
            IntegerField::new('startYear', 'Année de début')
                ->setNumberFormat('%d'),                 
            IntegerField::new('endYear', 'Année de fin')
                ->setNumberFormat('%d')
                ->setRequired(false)
                ->setEmptyData('En cours'),
            AssociationField::new('studentProfile', 'Étudiants rattachés')
                ->hideOnIndex(),
        ];
    }
}