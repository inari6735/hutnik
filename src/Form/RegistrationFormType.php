<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label_attr' => [
                    'class' => 'pb-2'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'email'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label_attr' => [
                    'class' => 'pb-2'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'email',
                    'autocomplete' => 'new-password'
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Twoje hasło musi zawierać co najmniej {{ limit }} znaków',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('first_name', TextType::class, [
                'label_attr' => [
                    'class' => 'pb-2'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'email'
                ],
                'label' => 'Imię'
            ])
            ->add('last_name', TextType::class, [
                'label_attr' => [
                    'class' => 'pb-2'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'type' => 'email'
                ],
                'label' => 'Nazwisko'
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
