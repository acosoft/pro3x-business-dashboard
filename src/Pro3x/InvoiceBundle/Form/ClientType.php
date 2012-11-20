<?php

namespace Pro3x\InvoiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Naziv'))
            ->add('address', 'text', array('label' => 'Adresa', 'required' => false))
            ->add('location', 'text', array('label' => 'Lokacija', 'required' => false))
            ->add('taxNumber', 'text', array('label' => 'OIB', 'required' => false))
            ->add('phone', 'text', array('label' => 'Telefon', 'required' => false))
            ->add('cellPhone', 'text', array('label' => 'Mobitel', 'required' => false))
            ->add('email', 'text', array('label' => 'Email', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\InvoiceBundle\Entity\Client'
        ));
    }

    public function getName()
    {
        return 'pro3x_invoicebundle_clienttype';
    }
}
