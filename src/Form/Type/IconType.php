<?php

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class IconType extends \Symfony\Component\Form\Extension\Core\Type\TextType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'label' => 'Icon',
            'html5' => false,
            'widget' => 'single_text',
            'attr' => ['class' => 'icon-picker', 'autocomplete' => 'disabled'],
            'help' => '',
        ]);
    }
}
