<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Recipe\IngredientGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

class IngredientGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IngredientGroup::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name', t('Name'));
        yield SlugField::new('slug')
            ->setTargetFieldName('name')
            ->hideOnForm()
            ->hideOnIndex()
        ;
        yield IntegerField::new('priority', t('Priority'));
    }
}
