<?php

declare(strict_types=1);

namespace App\Form\Update;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AccountUpdateSettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Team
            ->add('designation', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'Designation :',
                'empty_data' => '',
            ])
            ->add('about', TextareaType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'About :',
                'empty_data' => '',
                'attr' => ['rows' => 6],
            ])

            // Profil
            ->add('avatarFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => false,
                'imagine_pattern' => 'scale',
                'label' => 'Profile picture',
                'translation_domain' => 'messages',
            ])
            ->add('nickname', TextType::class, [
                'purify_html' => true,
                'required' => true,
                'label' => 'Username :',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 30]),
                ],
                'attr' => ['readonly' => true],
            ])
            ->add('firstname', TextType::class, [
                'purify_html' => true,
                'required' => true,
                'label' => 'First name :',
                'empty_data' => '',
            ])
            ->add('lastname', TextType::class, [
                'purify_html' => true,
                'required' => true,
                'label' => 'Last name :',
                'empty_data' => '',
            ])

            // Contact
            ->add('email', EmailType::class, [
                'purify_html' => true,
                'required' => true,
                'label' => 'Contact email address :',
                'empty_data' => '',
                'constraints' => [
                    new Email(),
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'max' => 180]),
                ],
                'attr' => ['readonly' => true],
                'help' => 'Enter the email address to be reached for inquiries',
            ])
            ->add('phone', TextType::class, [
                'purify_html' => true,
                'required' => false,
                'label' => 'Contact phone number :',
                'empty_data' => '',
                'help' => 'Enter the phone number to be called for inquiries',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', User::class);
    }
}
