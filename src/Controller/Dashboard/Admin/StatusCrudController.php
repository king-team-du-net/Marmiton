<?php

namespace App\Controller\Dashboard\Admin;

use App\Controller\Dashboard\Admin\Field\StatusField;
use App\Controller\Dashboard\Admin\Traits\ReadOnlyTrait;
use App\Entity\Recipe\Status;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

use function Symfony\Component\Translation\t;

class StatusCrudController extends AbstractCrudController
{
    use ReadOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Status::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield StatusField::new('name', t('Name'));
    }
}
