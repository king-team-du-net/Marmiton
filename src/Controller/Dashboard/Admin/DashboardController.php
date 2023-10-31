<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\Admin;

use App\Entity\HasRoles;
use App\Entity\Pages\Contact;
use App\Entity\Pages\Faq;
use App\Entity\Pages\Page;
use App\Entity\Recipe\Ingredient;
use App\Entity\Recipe\IngredientGroup;
use App\Entity\Recipe\Recipe;
use App\Entity\Recipe\Source;
use App\Entity\Recipe\Status;
use App\Entity\Recipe\Tag;
use App\Entity\Recipe\Unit;
use App\Entity\Recipe\UserRecipe;
use App\Entity\Review;
use App\Entity\Setting\AppLayoutSetting;
use App\Entity\Setting\Currency;
use App\Entity\Setting\HomepageHeroSetting;
use App\Entity\Setting\Setting;
use App\Entity\Testimonial;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function Symfony\Component\Translation\t;

/**
 * @method User getUser().
 */
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    #[IsGranted(HasRoles::MODERATOR)]
    #[Route('/%website_dashboard_path%/admin', name: 'dashboard_admin_index')]
    public function index(): Response
    {
        $controller = $this->isGranted(HasRoles::ADMIN) ? SettingCrudController::class : RecipeCrudController::class;

        $url = $this->adminUrlGenerator
            ->setController($controller)
            ->generateUrl()
        ;

        return $this->redirect($url);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl(t('Visit public website'), 'fas fa-undo', '/');

        if ($this->isGranted(HasRoles::SUPERADMIN)) {
            yield MenuItem::section(t('Users Settings'));
            yield MenuItem::subMenu(t('All accounts'), 'fas fa-user')->setSubItems([
                MenuItem::linkToCrud(t('Users'), 'fas fa-user-friends', User::class),
                MenuItem::linkToExitImpersonation(t('Stop impersonation'), 'fas fa-door-open'),
            ]);

            /*
            yield MenuItem::section(t('Comment Settings'));
            yield MenuItem::linkToCrud(t('Comments'), 'fas fa-comment', Comment::class);
            */

            yield MenuItem::section(t('FAQ Settings'));
            yield MenuItem::subMenu(t('FAQ'), 'fa fa-question-circle')->setSubItems([
                MenuItem::linkToCrud(t('All faqs'), 'fa fa-question-circle', Faq::class),
                MenuItem::linkToCrud(t('Add'), 'fas fa-plus', Faq::class)->setAction(Crud::PAGE_NEW),
            ]);

            /*
            yield MenuItem::section(t('Help Center Settings'));
            yield MenuItem::subMenu(t('Help Center'), 'fas fa-question')->setSubItems([
                MenuItem::linkToCrud(t('All articles'), 'fas fa-question', HelpCenterArticle::class),
                MenuItem::linkToCrud(t('All categories'), 'fas fa-list', HelpCenterCategory::class),
            ]);
            */

            yield MenuItem::section(t('Pages Settings'));
            yield MenuItem::subMenu(t('Pages'), 'fas fa-file')->setSubItems([
                MenuItem::linkToCrud(t('All pages'), 'fas fa-file', Page::class),
                MenuItem::linkToCrud(t('Add'), 'fas fa-plus', Page::class)->setAction(Crud::PAGE_NEW),
            ]);

            yield MenuItem::section(t('Contact Settings'));
            yield MenuItem::linkToCrud(t('Contact'), 'fas fa-message', Contact::class);

            yield MenuItem::section(t('Recipe settings'));
            yield MenuItem::subMenu(t('Recipe'), 'fa fa-list-check')->setSubItems([
                MenuItem::linkToCrud(t('All Recipes'), 'fa fa-list-check', Recipe::class),
                MenuItem::linkToCrud(t('Sources'), 'fa fa-share-from-square', Source::class),
                MenuItem::linkToCrud(t('Units'), 'fa fa-dice-one', Unit::class),
                MenuItem::linkToCrud(t('Ingredients'), 'fa fa-carrot', Ingredient::class),
                MenuItem::linkToCrud(t('Tags'), 'fa fa-tags', Tag::class),
                MenuItem::linkToCrud(t('Ingredient Group'), 'fa fa-cubes-stacked', IngredientGroup::class),
                MenuItem::linkToCrud(t('Status'), 'fas fa-info-circle', Status::class),
                MenuItem::linkToCrud(t('User Recipes'), 'fas fa-book-reader', UserRecipe::class),
                MenuItem::linkToCrud(t('All testimonials'), 'fas fa-star', Testimonial::class),
                MenuItem::linkToCrud(t('Add'), 'fas fa-plus', Testimonial::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud(t('All reviews'), 'fas fa-star', Review::class),
            ]);

            yield MenuItem::section(t('General settings'));
            yield MenuItem::subMenu(t('General'), 'fa fa-wrench')->setSubItems([
                MenuItem::linkToCrud(t('Settings'), 'fas fa-cog', Setting::class),
                MenuItem::linkToCrud(t('Layout settings'), 'fas fa-images', AppLayoutSetting::class),
                MenuItem::linkToCrud(t('Heros settings'), 'fas fa-image', HomepageHeroSetting::class),
                MenuItem::linkToCrud(t('Currency settings'), 'fas fa-money-check-dollar', Currency::class),
            ]);
        }

        /*
        if ($this->isGranted(HasRoles::MODERATOR)) {
            yield MenuItem::section(t('Blog Settings'));
            yield MenuItem::subMenu(t('Post Settings'), 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud(t('Post'), 'fas fa-newspaper', Article::class),
                MenuItem::linkToCrud(t('Add'), 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud(t('Tag'), 'fab fa-delicious', Badge::class),
                MenuItem::linkToCrud(t('Category'), 'fab fa-list', Category::class),
            ]);
        }*/

        yield MenuItem::section();
        yield MenuItem::linkToLogout(t('Sign Out'), 'fa fa-sign-out');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->renderContentMaximized()
            ->renderSidebarMinimized()
            ->generateRelativeUrls()
            /*
            ->setLocales([
                'en',
                Locale::new('fr', 'french', 'far fa-language'),
                Locale::new('es', 'spanish', 'far fa-language'),
                Locale::new('ar', 'arabic', 'far fa-language'),
                Locale::new('de', 'germany', 'far fa-language'),
                Locale::new('pt', 'portugais', 'far fa-language'),
            ])
            ->setTranslationDomain('admin')
            */
            ->setFaviconPath('uploads/layout/5ecac8821172a412596921.png')
            ->setTitle($this->getParameter('website_name'))
        ;
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->renderContentMaximized()
            ->showEntityActionsInlined()
            ->setDefaultSort([
                'id' => 'DESC',
            ])
        ;
    }

    /*
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $user = $this->getUser();

        return parent::configureUserMenu($user)
            ->setName($user->getNickname())
            ->displayUserName(false)
            ->addMenuItems([
                MenuItem::linkToRoute('My Profile', 'fa fa-id-card', '...', ['...' => '...']),
                MenuItem::linkToRoute('Settings', 'fa fa-user-cog', '...', ['...' => '...']),
                // MenuItem::section(),
                // MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ])
        ;
    }
    */
}
