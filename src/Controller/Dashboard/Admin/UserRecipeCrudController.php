<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\HasRoles;
use App\Entity\Recipe\UserRecipe;
use function Symfony\Component\Translation\t;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use App\Controller\Dashboard\Admin\Field\StatusField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

use App\Controller\Dashboard\Admin\Traits\ReadOnlyTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * @method User getUser()
 */
class UserRecipeCrudController extends AbstractCrudController
{
    use ReadOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return UserRecipe::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::SUPERADMIN)
            ->setEntityLabelInSingular(t('User'))
            ->setEntityLabelInPlural(t('Users'))
            ->setDefaultSort(['user' => 'ASC', 'recipe' => 'ASC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('user', t('User'));
        yield TextField::new('recipe', t('Recipe'));
        yield TextareaField::new('comment', t('Comment'));
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
        yield StatusField::new('status', t('Status'));
    }
}
