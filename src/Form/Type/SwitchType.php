<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SwitchType extends CheckboxType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'required' => false,
            'label_attr' => [
                'class' => 'form-check-label ps-3 ps-sm-4',
            ],
            'attr' => [
                // 'is' => 'input-switch',
                'class' => 'form-check-input flex-shrink-0',
            ],
            'row_attr' => [
                'class' => 'form-check form-switch d-flex pb-md-2 mb-4',
                'style' => 'padding-left: 30px;',
            ],
        ]);
    }
}
