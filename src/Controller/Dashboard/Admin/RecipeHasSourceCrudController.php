<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Recipe\RecipeHasSource;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

use function Symfony\Component\Translation\t;

class RecipeHasSourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecipeHasSource::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield UrlField::new('url', t('Url'));
        yield AssociationField::new('recipe', t('Recipe'));
        yield AssociationField::new('source', t('Source'));

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }
}
