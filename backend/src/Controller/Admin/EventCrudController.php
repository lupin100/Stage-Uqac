<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Enum\EventEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Événement')
            ->setEntityLabelInPlural('Événements')
            ->setDefaultSort(['startDate' => 'DESC']); 
    }

    public function configureFields(string $pageName): iterable
    {
        $typeChoices = [];
        foreach (EventEnum::cases() as $case) {
            $typeChoices[$case->value] = $case;
        }

        return [
            IdField::new('id')->hideOnForm(),      
            TextField::new('title', 'Titre de l\'événement'),
            DateTimeField::new('startDate', 'Date de début'),
            DateTimeField::new('endDate', 'Date de fin'),
            ChoiceField::new('eventType', 'Type d\'événement')
                ->setChoices($typeChoices)
                ->formatValue(fn ($value) => $value?->value),
            TextEditorField::new('content', 'Contenu de l\'événement'),
        ];
    }
}