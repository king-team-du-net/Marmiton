<?php

declare(strict_types=1);

namespace App\Form\Blog;

use App\Entity\Blog\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ArticleCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Comment *',
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input', 'rows' => 6],
                'help' => 'Comments not complying with our Code of Conduct will be moderated.',
            ])
            ->add('isRGPD', CheckboxType::class, [
                'label' => 'Yes, I agree to privacy policy',
                'data' => true, // Default checked
                'constraints' => [
                    new NotBlank(['message' => "Please don't leave the rgpd blank!"]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
