<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Recipe\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield BooleanField::new('menu', t('Menu'))->renderAsSwitch();
        yield TextField::new('name', t('Name'));
        yield TextEditorField::new('content', t('Content'));
        yield AssociationField::new('parent', t('Parent'));
    }
}
