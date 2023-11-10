<?php

namespace App\Form\Update;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountUpdateAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            // Address
            ->add('addressline1', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => false,
            ])
            ->add('addressline2', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => false,
            ])
            ->add('city', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => false,
            ])
            ->add('state', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => false,
            ])
            ->add('postalcode', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => false,
            ])
            ->add('countrycode', CountryType::class, [
                'required' => true,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
