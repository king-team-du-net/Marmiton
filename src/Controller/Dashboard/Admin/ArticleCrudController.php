<?php

namespace App\Controller\Dashboard\Admin;

use App\Entity\HasRoles;
use App\Entity\Blog\Article;
use Doctrine\ORM\EntityManagerInterface;
use function Symfony\Component\Translation\t;
use Symfony\Component\HttpFoundation\Response;
use Vich\UploaderBundle\Form\Type\VichFileType;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use Symfony\Component\Validator\Constraints\Length;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Validator\Constraints\NotBlank;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Validator\Constraints\GreaterThan;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewArticle = Action::new('viewArticle', t('See the article'))
            ->setHtmlAttributes([
                'target' => '_blank',
            ])
            ->linkToCrudAction('viewArticle')
        ;

        return $actions
            ->add(Crud::PAGE_EDIT, $viewArticle)
            ->add(Crud::PAGE_INDEX, $viewArticle)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            // ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            // ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityPermission(HasRoles::MODERATOR)
            ->setSearchFields(['title'])
            ->setDefaultSort(['publishedAt' => 'DESC', 'title' => 'ASC'])
            ->setAutofocusSearch()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('createdAt', t('Creation date'))
            ->add('publishedAt', t('Published the'))
            ->add('author', t('Author'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel(t('Blog'));
        yield IdField::new('id')->onlyOnIndex();
        yield ImageField::new('imageName')
            ->setUploadDir('public/uploads/article/')
            ->setBasePath('/uploads/article')
            ->hideOnForm()
        ;
        yield TextField::new('imageFile')->setFormType(VichFileType::class)->onlyOnForms();
        yield TextField::new('title', t('Title'));
        yield SlugField::new('slug')
            ->setTargetFieldName('title')
            ->hideOnForm()
            ->hideOnIndex()
        ;
        yield TextareaField::new('content', t('Content'))
            ->setFormTypeOption('constraints', [
                new NotBlank(),
                new Length(['min' => 2000]),
            ])->hideOnIndex()
        ;
        yield IntegerField::new('views', t('Views'))->hideOnForm()->hideOnIndex();
        yield IntegerField::new('readtime', t('Reading time'))->hideOnIndex();
        yield AssociationField::new('badges', t('Badges'))->autocomplete()->onlyOnIndex();
        yield ArrayField::new('badges', t('Badges'))->onlyOnDetail();
        // yield AssociationField::new('badges', t('Badges'))->setFormTypeOptions(['by_reference' => false]);
        yield AssociationField::new('comments', t('Comments'))->autocomplete()->onlyOnIndex();
        yield ArrayField::new('comments', t('Comments'))->onlyOnDetail();
        yield AssociationField::new('author', t('Author'));

        yield FormField::addPanel(t('Publication date'));
        yield DateTimeField::new('publishedAt', t('Published At'))
            ->setFormTypeOption('constraints', [
                // new NotBlank(),
                new GreaterThan(new \DateTimeImmutable()),
            ])
        ;

        yield FormField::addPanel(t('Date'))->hideOnForm();
        yield DateTimeField::new('createdAt', t('Creation date'))->hideOnForm()->onlyOnDetail();
        yield DateTimeField::new('updatedAt', t('Last modification'))->hideOnForm()->onlyOnDetail();
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        /** @var Article $entity */
        $entity = $entityInstance;

        $entity->setAuthor($this->getUser());

        parent::persistEntity($entityManager, $entity);
    }

    public function viewArticle(AdminContext $context): Response
    {
        /** @var Article $entity */
        $entity = $context->getEntity()->getInstance();

        return $this->redirectToRoute('blog_show', [
            'slug' => $entity->getSlug(),
        ]);
    }
}
