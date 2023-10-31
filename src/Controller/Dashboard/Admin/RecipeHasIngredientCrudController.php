<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Recipe\RecipeHasIngredient;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

use function Symfony\Component\Translation\t;

class RecipeHasIngredientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeHasIngredient::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield BooleanField::new('optional', t('Optional'));
        yield AssociationField::new('recipe', t('Recipe'));
        yield AssociationField::new('ingredient', t('Ingredient'));
        yield NumberField::new('quantity', t('Quantity'));
        yield AssociationField::new('unit', t('Unit'));
        yield AssociationField::new('ingredientGroup', t('Ingredient group'));
    }
}
