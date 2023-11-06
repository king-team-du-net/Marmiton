<?php

declare(strict_types=1);

namespace App\Form\Update;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class AccountUpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $editAttr = [
            'minlength' => 8,
        ];

        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current password',
                'constraints' => [
                    new NotBlank(),
                    new UserPassword(),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'translation_domain' => 'message',
                'invalid_message' => 'Your password should be similar to the confirmation.',
                'required' => true,
                'constraints' => [
                    new NotBlank(['normalizer' => 'trim']),
                    new Length([
                        'min' => 8,
                        'max' => 255,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'htmlPattern' => '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$',
                        'groups' => ['password'],
                    ]),
                ],
                'first_options' => ['label' => 'New password :', 'attr' => [...$editAttr, ...['placeholder' => '**********']]],
                'second_options' => ['label' => 'Confirm new password :', 'attr' => [...$editAttr, ...['placeholder' => '**********']]],
            ])
        ;
    }
}
