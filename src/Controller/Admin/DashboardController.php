<?php

namespace App\Controller\Admin;

use App\Entity\Student;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;

use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;


use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    // The only requirement is to define the route in a controller method named index(), which is the one called by EasyAdmin to render the dashboard:
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(StudentCrudController::class)->generateUrl();

        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('SymfonySessions');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'home');
        yield MenuItem::linkToCrud('Categories', 'fas fa-map-marker-alt', Category::class);
        yield MenuItem::linkToCrud('Students', 'fas fa-comments', Student::class);
    }






    // public function configureActions(Actions $actions): Actions
    // {
    //     $viewInvoice = Action::new('invoice', 'View invoice', 'fa fa-file-invoice')
    //         ->linkToCrudAction('renderInvoice');

    //     return $actions
    //         // ...
    //         ->add(Crud::PAGE_DETAIL, $viewInvoice)
    //         // use the 'setPermission()' method to set the permission of actions
    //         // (the same permission is granted to the action on all pages)
    //         ->setPermission('invoice', 'ROLE_TEACHER')

    //         // you can set permissions for built-in actions in the same way
    //         ->setPermission(Action::NEW, 'ROLE_ADMIN')
    //     ;
    // }

    // public function getFields(string $action): iterable
    // {
    //     return [
    //         IdField::new('id'),
    //         TextField::new('price'),
    //         IntegerField::new('stock'),
    //         // users must have this permission/role to see this field
    //         IntegerField::new('sales')->setPermission('ROLE_ADMIN'),
    //         FloatField::new('commission')->setPermission('ROLE_FINANCE'),
    //         // ...
    //     ];
    // }


    // public function configureCrud(Crud $crud): Crud
    // {
    //     return $crud
    //         ->setEntityPermission('ROLE_ADMIN')
    //         // ...
    //     ;
    // }
}


// https://symfony.com/bundles/EasyAdminBundle/current/dashboards.html#customizing-the-dashboard-contents