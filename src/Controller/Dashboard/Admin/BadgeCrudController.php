<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Blog\Badge;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

class BadgeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Badge::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('Blog tag'));
        yield TextField::new('type', t('Type'));
        yield TextField::new('name', t('Name'));
        yield SlugField::new('slug')
            ->setTargetFieldName('name')
            ->hideOnForm()
        ;

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }
}
