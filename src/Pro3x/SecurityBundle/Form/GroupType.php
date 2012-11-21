<?php

namespace Pro3x\SecurityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Naziv grupe'))
            ->add('role', 'text', array('label' => 'Naziv uloge'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\SecurityBundle\Entity\Group'
        ));
    }

    public function getName()
    {
        return 'pro3x_securitybundle_grouptype';
    }
}
