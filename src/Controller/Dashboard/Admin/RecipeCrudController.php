<?php

namespace App\Controller\Dashboard\Admin;

use App\Controller\Dashboard\Admin\Traits\ReadOnlyTrait;
use App\Entity\Recipe\Recipe;
use App\Form\RecipeHasIngredientType;
use App\Form\RecipeHasSourceType;
use App\Form\StepType;
use App\Form\ThumbnailType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

class RecipeCrudController extends AbstractCrudController
{
    use ReadOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Recipe::class;
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
        yield IntegerField::new('views', t('Views'))->hideOnForm()->onlyOnDetail();
        yield BooleanField::new('enablereviews', t('Enable Reviews'))->onlyOnDetail();
        yield BooleanField::new('draft', t('Draft'));
        yield IntegerField::new('cooking', t('Cooking'));
        yield IntegerField::new('break', t('Break'));
        yield IntegerField::new('preparation', t('Preparation'));
        yield CollectionField::new('steps', t('Steps'))
            ->setEntryType(StepType::class)
            ->allowDelete()
            ->allowAdd();
        yield CollectionField::new('thumbnails', t('Thumbnails'))
            ->setEntryType(ThumbnailType::class)
            ->allowDelete()
            ->allowAdd();
        yield CollectionField::new('recipeHasIngredients', t('The recipe contains ingredients'))
            ->setEntryType(RecipeHasIngredientType::class)
            ->allowDelete()
            ->allowAdd();
        yield CollectionField::new('recipeHasSources', t('The recipe has sources'))
            ->setEntryType(RecipeHasSourceType::class)
            ->allowDelete()
            ->allowAdd();
        yield AssociationField::new('tags', t('Tags'))->setFormTypeOptions(['by_reference' => false]);

        yield AssociationField::new('reviews', t('Reviews'))->onlyOnIndex();
        yield ArrayField::new('reviews', t('Reviews'))->onlyOnDetail();
        yield AssociationField::new('addedtofavoritesby', t('Favorites'))->onlyOnIndex();
        yield ArrayField::new('addedtofavoritesby', t('Favorites'))->onlyOnDetail();

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }
}
