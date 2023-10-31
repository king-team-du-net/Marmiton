<?php

declare(strict_types=1);

namespace App\Form\Pages;

use App\Entity\Pages\Contact;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname', TextType::class, [
                'label' => 'Full name :',
                'required' => true,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
            ])
            ->add('company', TextType::class, [
                'label' => 'Company :',
                'required' => true,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Subject :',
                'required' => true,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail address :',
                'required' => true,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Phone :',
                'required' => false,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Comments :',
                'required' => true,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input', 'rows' => 6],
            ])
            ->add('recaptcha', EWZRecaptchaType::class, [
                'attr' => [
                    'options' => [
                        'theme' => 'light',
                        'type' => 'image',
                        'size' => 'normal',
                    ],
                ],
                'mapped' => false,
                'constraints' => [
                    new RecaptchaTrue(['groups' => 'Contact']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
