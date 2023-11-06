<?php

namespace App\Controller\Dashboard\Admin;

use App\Controller\Dashboard\Admin\Traits\ReadOnlyTrait;
use App\Entity\HasRoles;
use App\Entity\Pages\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

use function Symfony\Component\Translation\t;

class ContactCrudController extends AbstractCrudController
{
    use ReadOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::SUPERADMIN)
            ->setSearchFields(null)
            ->setEntityLabelInSingular(t('Contact'))
            ->setEntityLabelInPlural(t('Contacts'))
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('Information'));
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('fullname', t('Fullname'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 4, 'max' => 100]),
            ])
        ;
        yield EmailField::new('email', t('Email'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new NotNull(),
                new Email(),
                new Length(min: 5, max: 180),
            ])
        ;
        yield TextField::new('phone', t('Phone Number'))
            ->setFormTypeOption('constraints', [
                new Length(min: 10, max: 10),
                new Regex(['pattern' => '/^0[0-9]{9}$/']),
            ])->hideOnIndex()
        ;
        yield TextField::new('company', t('Company'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 10, 'max' => 100]),
            ])
        ;
        yield TextField::new('subject', t('Subject'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 10, 'max' => 100]),
            ])
        ;
        yield TextareaField::new('message', t('Message'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 10]),
            ])
            ->hideOnIndex()
        ;

        yield FormField::addPanel(t('Actived'));
        yield BooleanField::new('isSend', t('Send'));

        yield FormField::addPanel(t('IP adress'));
        yield TextField::new('ip', t('IP'))->hideOnIndex();

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }
}
