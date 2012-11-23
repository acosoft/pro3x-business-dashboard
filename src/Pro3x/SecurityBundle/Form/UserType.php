<?php

namespace Pro3x\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('displayName', 'text', array('label' => 'Naziv korisnika', 'required' => false))
            ->add('username', 'text', array('label' => 'Korisničko ime'))
            ->add('email', 'text')
            ->add('active', 'choice', array('choices' => array('1' => "Aktivan", 0 => "Ne aktivan"), 'label' => 'Aktiviran'))
			->add('oib', 'text', array('required' => false, 'label' => 'OIB'))
            ->add('password', 'repeated', array('first_name' => 'password', 'second_name' => 'confirm_password', 'type' => 'password', 'required' => false, 
				'first_options' => array('label' => 'Zaporka'),
				'second_options' => array('label' => 'Ponovite zaporku')))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\SecurityBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'pro3x_securitybundle_usertype';
    }
}
