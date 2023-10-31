<?php

namespace App\Controller\Dashboard\Admin;

use App\Controller\Dashboard\Admin\Traits\ReadOnlyTrait;
use App\Entity\HasRoles;
use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\Translation\t;

class ReviewCrudController extends AbstractCrudController
{
    use ReadOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::SUPERADMIN)
            ->setEntityLabelInSingular(t('Review'))
            ->setEntityLabelInPlural(t('Reviews'))
            ->setDefaultSort(['createdAt' => 'DESC', 'user' => 'ASC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('Recipe'));
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('headline', t('Headline'));
        yield SlugField::new('slug')
            ->setTargetFieldName('headline')
            ->hideOnForm()
            ->hideOnIndex()
        ;
        yield AssociationField::new('user', t('User'))->autocomplete();
        yield AssociationField::new('recipe', t('Recipe'))->autocomplete();
        yield TextareaField::new('details', t('Details'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 25]),
            ])->hideOnIndex()
        ;
        yield ChoiceField::new('rating', t('Your rating (out of 5 stars) '))
            ->allowMultipleChoices(false)
            ->renderExpanded(true)
            ->renderAsBadges()
            ->setChoices([
                '5 stars' => 5,
                '4 stars' => 4,
                '3 stars' => 3,
                '2 stars' => 2,
                '1 star' => 1,
            ])
        ;

        yield FormField::addPanel(t('Actived'));
        yield BooleanField::new('visible', t('Visible'));

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('deletedAt', t('Deleted At'))->onlyOnDetail();
    }
}
