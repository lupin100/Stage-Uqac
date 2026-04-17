<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin_dashboard')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GRI UQAC - Administration')
            ->setFaviconPath('/images/favicon.svg');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Retour au site', 'fa fa-arrow-left', $this->getParameter('app.frontend_url'));
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        
        yield MenuItem::section('Contenu éditorial');
        yield MenuItem::linkTo(NewsCrudController::class, 'Nouvelles', 'fas fa-newspaper');
        yield MenuItem::linkTo(EventCrudController::class, 'Évènements', 'fas fa-calendar-day');

        yield MenuItem::section('Membres & Recherche');
        yield MenuItem::linkTo(PersonCrudController::class, 'Personnes', 'fas fa-user-friends');
        yield MenuItem::linkTo(ContributorCrudController::class, 'Contributeurs', 'fas fa-pen-fancy');
        yield MenuItem::linkTo(PublicationCrudController::class, 'Publications', 'fas fa-book');
        yield MenuItem::linkTo(ProjectCrudController::class, 'Projets', 'fas fa-tasks');

        yield MenuItem::section('Université & Études');
        yield MenuItem::linkTo(InstitutionCrudController::class, 'Institutions', 'fas fa-university');
        yield MenuItem::linkTo(DepartementCrudController::class, 'Départements', 'fas fa-building');
        yield MenuItem::linkTo(StudentProfileCrudController::class, 'Profils Étudiants', 'fas fa-user-graduate');
        yield MenuItem::linkTo(StudentDegreeCrudController::class, 'Diplômes', 'fas fa-certificate');

        yield MenuItem::section('Paramètres');
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs (Admin)', 'fas fa-user-lock');
    }
}
