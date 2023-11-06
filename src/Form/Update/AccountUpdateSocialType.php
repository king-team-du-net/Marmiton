<?php

declare(strict_types=1);

namespace App\Form\Update;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Url;

class AccountUpdateSocialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            // Social Media
            ->add('externallink', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'External link :',
                'empty_data' => '',
                'help' => 'If your has a dedicated website, enter its url here',
            ])
            ->add('twitterurl', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'Twitter :',
                'empty_data' => '',
                'constraints' => [
                    new Url(),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('instagramurl', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'Instagram :',
                'empty_data' => '',
                'constraints' => [
                    new Url(),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('facebookurl', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'Facebook :',
                'empty_data' => '',
                'constraints' => [
                    new Url(),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('googleplusurl', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'Google Plus :',
                'empty_data' => '',
                'constraints' => [
                    new Url(),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('linkedinurl', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'LinkedIn :',
                'empty_data' => '',
                'constraints' => [
                    new Url(),
                    new Length(['max' => 255]),
                ],
            ])

            // Video
            ->add('youtubeurl', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'Youtube video url :',
                'empty_data' => '',
                'help' => 'If you have an Youtube video that represents your activities, add it in the standard format: https://www.youtube.com/watch?v=FzG4uDgje3M',
                'constraints' => [
                    new Url(),
                    new Length(['max' => 255]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', User::class);
    }
}
