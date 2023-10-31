<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Recipe\Step;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

use function Symfony\Component\Translation\t;

class StepCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Step::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield IntegerField::new('priority', t('Priority'));
        yield TextEditorField::new('content', t('Content'));
        yield AssociationField::new('recipe', t('Recipe'));
        yield AssociationField::new('thumbnails', t('Thumbnails'));

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }
}
