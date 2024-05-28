<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\User;
use App\Entity\UserRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', EmailType::class,[
                'label' => 'Email Address',
                'constraints' => [
                    new Email ([
                        'message' => "Veuillez entrer une adresse email valide"
                    ]),
                    new NotBlank([
                        'message' => "Ce champs ne peut être laissé vide"
                    ])
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label'=>'Password'],
                'second_options' => ['label' => 'Repeat Password']
            ])
            ->add('role', EntityType::class, [
                'class' => UserRole::class,
                'choice_label' => 'name',
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'name',
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
