<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\HasRoles;
use App\Entity\Setting\HomepageHeroSetting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichFileType;

use function Symfony\Component\Translation\t;

class HomepageHeroSettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomepageHeroSetting::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::SUPERADMIN)
            ->setSearchFields(null)
            ->setEntityLabelInSingular(t('Hero'))
            ->setEntityLabelInPlural(t('Heros'))
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('Information'));
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('title', t('Title'));
        yield TextareaField::new('paragraph', t('Paragraph'))->hideOnIndex();
        yield TextareaField::new('content', t('Content'))->hideOnIndex();

        yield FormField::addPanel(t('Custom Background'));
        yield ImageField::new('customBackgroundName')
            ->setUploadDir('public/uploads/home/hero/')
            ->setBasePath('/uploads/home/hero')
            ->hideOnForm()
        ;
        yield TextField::new('customBackgroundFile')->setFormType(VichFileType::class)->onlyOnForms();

        yield AssociationField::new('recipes', t('Recipes'))->onlyOnIndex();
        yield ArrayField::new('recipes', t('Recipes'))->onlyOnDetail();
        yield AssociationField::new('users', t('Users'))->autocomplete();

        yield FormField::addPanel(t('Show Search'));
        yield BooleanField::new('show_search_box', t('Search Box'));

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->hideOnIndex();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->hideOnIndex();
    }
}
