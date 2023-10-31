<?php

declare(strict_types=1);

namespace App\Form\Blog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class SearchedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-search',
                    'placeholder' => 'Enter your search here...',
                    'aria-label' => 'Search',
                ],
                'constraints' => [new NotBlank()],
                'label_attr' => ['class' => 'form-label sr-only'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
