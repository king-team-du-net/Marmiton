<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\HasRoles;
use App\Entity\Pages\Page;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\Translation\t;

class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::SUPERADMIN)
            ->setEntityLabelInSingular(t('Page'))
            ->setEntityLabelInPlural(t('Pages'))
            ->setDefaultSort(['createdAt' => 'DESC', 'title' => 'ASC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('Information'));
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('title', t('Title'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 4, 'max' => 128]),
            ])
        ;
        yield SlugField::new('slug')
            ->setTargetFieldName('title')
            ->hideOnIndex()
        ;
        yield TextEditorField::new('content', t('Content'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 2000]),
            ])
            ->hideOnIndex()
        ;
        yield IntegerField::new('views', t('Views'))->hideOnForm();

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }
}
