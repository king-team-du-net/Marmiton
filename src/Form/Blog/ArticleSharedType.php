<?php

declare(strict_types=1);

namespace App\Form\Blog;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ArticleSharedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sender_name', TextType::class, [
                'label' => 'Full name :',
                'required' => true,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('sender_email', EmailType::class, [
                'label' => 'E-mail address :',
                'required' => true,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Length(min: 5, max: 180),
                ],
            ])
            ->add('receiver_email', EmailType::class, [
                'label' => "Friend's email :",
                'required' => true,
                'empty_data' => '',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input'],
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Length(min: 5, max: 180),
                ],
            ])
            ->add('sender_comments', TextareaType::class, [
                'label' => 'Comments :',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-input', 'rows' => 6],
                'help' => 'Leave it blank if you want (optional).',
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
                    new RecaptchaTrue(['groups' => 'ArticleShared']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
