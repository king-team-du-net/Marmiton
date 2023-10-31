<?php

namespace App\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

class TagsInputType extends \Symfony\Component\Form\Extension\Core\Type\TextType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'label' => 'Tags',
            'html5' => false,
            'widget' => 'single_text',
            'attr' => ['class' => 'tags-input'],
            'help' => 'To help articles, recipes find your content quickly, enter some keywords that identify your event (press Enter after each entry)',
        ]);
    }
}
