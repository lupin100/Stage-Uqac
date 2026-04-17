<?php

namespace App\Controller\Admin;

use App\Entity\Publication;
use App\Enum\PublicationEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class PublicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Publication::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Publication')
            ->setEntityLabelInPlural('Publications')
            ->setDefaultSort(['year' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        $typeChoices = [];
        foreach (PublicationEnum::cases() as $case) {
            $typeChoices[$case->value] = $case;
        }

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre de la publication'),            
            IntegerField::new('year', 'Année de publication'),
            ChoiceField::new('publicationType', 'Type de publication')
                ->setChoices($typeChoices)
                ->formatValue(fn ($value) => $value?->value),
            UrlField::new('externalUrl', 'Lien externe (DOI, PDF, etc.)')
                ->setRequired(false),
            AssociationField::new('contributors', 'Contributeurs associés')
                ->hideOnIndex(),
        ];
    }
}