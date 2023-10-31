<?php

namespace App\Controller\Dashboard\Admin;

use App\Controller\Dashboard\Admin\Traits\ReadOnlyTrait;
use App\Entity\Blog\Comment;
use App\Entity\HasRoles;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use function Symfony\Component\Translation\t;

class CommentCrudController extends AbstractCrudController
{
    use ReadOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::SUPERADMIN)
            ->setEntityLabelInSingular(t('Comment'))
            ->setEntityLabelInPlural(t('Comments'))
            ->setSearchFields(['author', 'email', 'content'])
            ->setDefaultSort(['publishedAt' => 'DESC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('publishedAt')
            ->add('article')
            ->add('author')
            ->add('isApproved')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('Information'));
        yield IdField::new('id')->onlyOnIndex();
        yield TextareaField::new('content', t('Content'))->onlyOnDetail();

        yield FormField::addPanel(t('Association'));
        yield AssociationField::new('article', t('Article'));
        yield AssociationField::new('author', t('Author'));

        yield FormField::addPanel(t('Actived'));
        yield BooleanField::new('isApproved', t('Approved'));
        yield BooleanField::new('isRGPD', t('RGPD'))->hideOnForm();

        yield FormField::addPanel(t('Date'));
        yield DateTimeField::new('publishedAt', t('Published date'))->onlyOnDetail();

        yield FormField::addPanel(t('IP adress'));
        yield TextField::new('ip', t('IP'))->hideOnIndex();
    }
}
