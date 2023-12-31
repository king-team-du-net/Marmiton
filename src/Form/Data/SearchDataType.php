<?php

declare(strict_types=1);

namespace App\Form\Data;

use App\Entity\Blog\Category;
use App\Entity\Data\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keywords', TextType::class, [
                'attr' => [
                    'placeholder' => 'Keywords',
                    'aria-label' => 'Search',
                ],
                'empty_data' => '',
                'required' => false,
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => 'name',
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
