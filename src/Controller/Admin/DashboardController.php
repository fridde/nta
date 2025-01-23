<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\Box;
use App\Entity\Item;
use App\Entity\Qualification;
use App\Entity\School;
use App\Entity\Topic;
use App\Entity\User;
use App\Utils\RepoContainer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{
    private const array MENU = [
        'tables' => [
            ['Bokningar', 'book', Booking::class],
            ['Användare', 'users', User::class],
            ['Lådor', 'boxes', Box::class],
            ['Teman', 'question', Topic::class],
            ['Skolor', 'school', School::class],
            ['Kompetens', 'award', Qualification::class],
            ['Föremål', 'flask', Item::class]
        ],
        'routes' => [
//            ['Mejlutskick', 'envelope', 'tools_send_mail'],
//            ['Skolor besöksordning', 'sort-numeric-down', 'tools_order_schools'],
//            ['Skapa API-keys', 'key', 'tools_create_api_keys'],
//            ['Kolla upp användare', 'magnifying-glass' ,'tools_lookup_profile', ['mail' => '1']],
//            ['Extra inställningar', 'cogs', 'tools_extra_settings'],
//            ['Logg', 'th-list', 'tools_show_log'],
        ]
    ];

    public function __construct
    (
        protected RepoContainer $rc
    )
    {
    }


    #[Route('/admin/', name: 'admin')]
    public function index(): Response
    {
        return parent::index();

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
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('NTA');
    }

    public function configureMenuItems(): iterable
    {
        $extractCrud = fn($v) => MenuItem::linkToCrud($v[0], 'fas fa-' . $v[1], $v[2]);
        $extractRoute = fn($v) => MenuItem::linkToRoute($v[0], 'fas fa-' . $v[1], $v[2], $v[3] ?? []);

        return [
            //MenuItem::linkToDashboard('Översikt', 'fa fa-home'),
            MenuItem::subMenu('Tabeller', 'fas fa-table')
                ->setSubItems(array_map($extractCrud, self::MENU['tables'])),
            MenuItem::subMenu('Verktyg', 'fas fa-tools')
                ->setSubItems(array_map($extractRoute, self::MENU['routes'])),
//            MenuItem::subMenu('Skolsidor', 'fas fa-school')
//                ->setSubItems($this->getSchoolsAsMenuItems()),

            MenuItem::section(),
            //MenuItem::linkToLogout('Logout', 'fas fa-sign-out-alt')
        ];

    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->showEntityActionsInlined()
            ->setPaginatorPageSize(200)
            ->setDateFormat('YYYY-MM-dd')
            ->setDateTimeFormat('YYYY-MM-dd HH:mm');
    }

}
