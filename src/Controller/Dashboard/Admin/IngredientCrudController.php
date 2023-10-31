<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Recipe\Ingredient;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

class IngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ingredient::class;
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
        yield TextEditorField::new('content', t('Content'));
        yield BooleanField::new('vegan', t('Vegan'));
        yield BooleanField::new('vegetarian', t('Vegetarian'));
        yield BooleanField::new('dairyFree', t('Dairy free'));
        yield BooleanField::new('glutenFree', t('Gluten free'));

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }
}
