<?php

namespace App\Controller\Dashboard\Admin;

use App\Controller\Dashboard\Admin\Traits\CreateReadDeleteTrait;
use App\Entity\HasRoles;
use App\Entity\Testimonial;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;

use function Symfony\Component\Translation\t;

class TestimonialCrudController extends AbstractCrudController
{
    use CreateReadDeleteTrait;

    public static function getEntityFqcn(): string
    {
        return Testimonial::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::SUPERADMIN)
            ->setEntityLabelInSingular(t('Testimonial'))
            ->setEntityLabelInPlural(t('Testimonials'))
            ->setDefaultSort(['createdAt' => 'DESC', 'user' => 'ASC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('User'));
        yield IdField::new('id')->onlyOnIndex();

        /*
        yield ImageField::new('photoName')
            ->setUploadDir('public/uploads/testimonial/')
            ->setBasePath('/uploads/testimonial')
            ->hideOnForm()
        ;
        yield TextField::new('photoFile')->setFormType(VichFileType::class)->onlyOnForms();
        */

        yield FormField::addPanel(t('Avatar'));
        yield ImageField::new('avatarName')
            ->setUploadDir('public/uploads/user/')
            ->setBasePath('/uploads/user')
            ->hideOnForm()
        ;
        yield TextField::new('avatarFile')->setFormType(VichFileType::class)->onlyOnForms();

        yield AssociationField::new('user', t('User'))->autocomplete();
        yield TextareaField::new('comment', t('Comment'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 2000]),
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
        yield BooleanField::new('isOnline', t('Online'));

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }
}
