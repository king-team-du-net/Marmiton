<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Testimonial;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'label' => 'Your rating (out of 5 stars)',
                'choices' => ['5 stars' => 5, '4 stars' => 4, '3 stars' => 3, '2 stars' => 2, '1 star' => 1],
                'label_attr' => ['class' => 'radio-custom radio-inline'],
            ])
            ->add('comment', TextareaType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'Comment :',
                'empty_data' => '',
                'attr' => ['rows' => 10],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Testimonial::class,
        ]);
    }
}
