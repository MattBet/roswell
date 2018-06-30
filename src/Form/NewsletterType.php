<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, array(
                'attr' => array('class' => '', 'placeholder' => 'First Name'),
                'label' => false,
            ))
            ->add('email_address', EmailType::class, array(
                'attr' => array('class' => '', 'placeholder' => 'Email Address'),
                'label' => false,
            ))
            ->add('sign_up', SubmitType::class, array(
                'attr' => array('class' => 'submitBtn')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
