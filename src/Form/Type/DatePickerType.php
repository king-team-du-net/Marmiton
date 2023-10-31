<?php

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DatePickerType extends \Symfony\Component\Form\Extension\Core\Type\DateTimeType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'html5' => false,
            'widget' => 'single_text',
            'attr' => ['class' => 'datepicker'],
        ]);
    }
}
