<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\Recipe\Unit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

class UnitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Unit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('singular', t('Singular'));
        yield TextField::new('plural', t('Plural'));
    }
}
