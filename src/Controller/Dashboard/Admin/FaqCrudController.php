<?php

declare(strict_types=1);

namespace App\Controller\Dashboard\Admin;

use App\Entity\Pages\Faq;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\NotBlank;

use function Symfony\Component\Translation\t;

class FaqCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Faq::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(t('Question'))
            ->setEntityLabelInPlural(t('Questions'))
            ->setDefaultSort(['question' => 'ASC'])
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('Faq'));
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('question', t('Question title'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
            ])
        ;
        yield TextEditorField::new('answer', t('Answer'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
            ])
            ->hideOnIndex()
        ;

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm();
    }
}
