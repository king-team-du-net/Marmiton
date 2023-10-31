<?php

namespace App\Form;

use App\Entity\Recipe\Ingredient;
use App\Entity\Recipe\IngredientGroup;
use App\Entity\Recipe\RecipeHasIngredient;
use App\Entity\Recipe\Unit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeHasIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class)
            ->add('optional', CheckboxType::class)
            ->add('ingredient', EntityType::class, options: [
                'class' => Ingredient::class,
            ])
            ->add('ingredientGroup', EntityType::class, options: [
                'class' => IngredientGroup::class,
            ])
            ->add('unit', EntityType::class, options: [
                'class' => Unit::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecipeHasIngredient::class,
        ]);
    }
}
