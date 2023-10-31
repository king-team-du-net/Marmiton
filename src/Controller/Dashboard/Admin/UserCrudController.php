<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\User;
use App\Entity\HasRoles;
use Doctrine\ORM\QueryBuilder;
use function Symfony\Component\Translation\t;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\Validator\Constraints\Email;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\NotNull;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Validator\Constraints\NotBlank;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

/**
 * @method User getUser()
 */
class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityRepository $entityRepo
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(
        SearchDto $searchDto,
        EntityDto $entityDto,
        FieldCollection $fields,
        FilterCollection $filters
    ): QueryBuilder {
        $userId = $this->getUser()->getId();

        $response = $this->entityRepo->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.id != :userId')->setParameter('userId', $userId);

        return $response;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::SUPERADMIN)
            ->setEntityLabelInSingular(t('User'))
            ->setEntityLabelInPlural(t('Users'))
            ->setDefaultSort(['firstname' => 'ASC', 'lastname' => 'ASC'])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $logAs = Action::new('logAs', t('Login as'))
            ->displayAsLink()
            ->linkToRoute('main', fn (User $user) => ['_switch_user' => $user->getEmail()])
        ;

        return $actions
            ->add(Crud::PAGE_INDEX, $logAs)
            ->add(Crud::PAGE_DETAIL, $logAs)

            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('User'));
        yield IdField::new('id')->onlyOnIndex();
        yield FormField::addPanel(t('Avatar'));
        yield ImageField::new('avatarName')
            ->setUploadDir('public/uploads/user/')
            ->setBasePath('/uploads/user')
            ->hideOnForm()
        ;
        yield TextField::new('avatarFile')->setFormType(VichFileType::class)->onlyOnForms();

        yield FormField::addPanel(t('Identify'));
        yield TextField::new('firstname', t('Firstname'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(min: 2, max: 20),
            ])
        ;
        yield TextField::new('lastname', t('Lastname'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(min: 2, max: 20),
            ])
        ;
        yield TextField::new('nickname', t('Nickname'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new NotNull(),
                new Length(min: 5, max: 30),
            ])
        ;
        yield SlugField::new('slug')
            ->setTargetFieldName('nickname')
            ->hideOnIndex()
            ->hideOnForm()
        ;
        yield TextField::new('plainPassword', t('Password'))
            ->setFormType(PasswordType::class)
            ->setFormTypeOption('constraints', [
                new NotBlank(),
            ])
            ->onlyWhenCreating()
        ;
        yield EmailField::new('email', t('Email'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new NotNull(),
                new Email(),
                new Length(min: 5, max: 180),
            ])
        ;

        yield FormField::addPanel(t('Details'));
        yield BooleanField::new('suspended', t('Suspended'));

        yield FormField::addPanel(t('Team'));
        yield BooleanField::new('team', t('Team'));
        yield ChoiceField::new('roles', t('Roles'))
            ->renderExpanded()
            ->renderAsBadges([
                HasRoles::SUPERADMIN => 'danger',
                HasRoles::ADMIN => 'primary',
                HasRoles::MODERATOR => 'secondary',
                HasRoles::DEFAULT => 'info',
            ])
            ->setChoices([
                'Super Admin' => HasRoles::SUPERADMIN,
                'Admin' => HasRoles::ADMIN,
                'Moderator' => HasRoles::MODERATOR,
                'User' => HasRoles::DEFAULT,
            ])
            ->allowMultipleChoices()
            ->setRequired(isRequired: false)
        ;
        yield TextareaField::new('about', t('About'))->hideOnIndex();
        yield TextareaField::new('designation', t('Designation'))->hideOnIndex();

        yield FormField::addPanel(t('Date'));
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('deletedAt', t('Deleted At'))->hideOnIndex();

        yield FormField::addPanel(t('User Recipes'))->hideOnForm();
        yield AssociationField::new('userRecipes', t('User Recipes'))
            ->onlyOnIndex();
        yield ArrayField::new('userRecipes', t('User Recipes'))
            ->onlyOnDetail();
    }
}
