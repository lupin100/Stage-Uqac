<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Enum\ProjectEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Projet')
            ->setEntityLabelInPlural('Projets')
            ->setDefaultSort(['title' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $thematicChoices = [];
        foreach (ProjectEnum::cases() as $case) {
            $thematicChoices[$case->value] = $case;
        }

        return [
            IdField::new('id')->hideOnForm(),            
            TextField::new('title', 'Titre du projet'),
            ChoiceField::new('thematic', 'Thématique')
                ->setChoices($thematicChoices)
                ->formatValue(fn ($value) => $value?->value),
            TextField::new('fundingSource', 'Source de financement')
                ->setRequired(false),
            BooleanField::new('isFinished', 'Projet terminé')
                ->renderAsSwitch(true),
            TextEditorField::new('summary', 'Résumé du projet')
                ->hideOnIndex(), // Masqué dans le tableau principal
            AssociationField::new('contributors', 'Contributeurs')
                ->hideOnIndex(),
        ];
    }
}