<?php
/**
 * Created by PhpStorm.
 * User: Egor
 * Date: 17.11.2018
 * Time: 22:27
 */

namespace App\Form;


use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\ImageValidator;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email',EmailType::class, [
                'label' => 'Email'
            ])
            ->add('firstname',TextType::class,  [
                'label' => 'Firstname'
            ])
            ->add('lastname',TextType::class, [
                'label' => 'Lastname'
            ])
            ->add('password',PasswordType::class,[
                'label' => 'Password'
            ])
            ->add('isblogger', CheckboxType::class,[
                'label' => 'I want to be a blogger!'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Зарегистрироваться!',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                    'data_class' => User::class
            ]);
    }
}