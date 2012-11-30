<?php

namespace Pro3x\InvoiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('location', 'entity', array(
				'label' => 'Lokacija blagajne', 
				'expanded' => false, 'property' => 'name', 
				'multiple' => false, 
				'class' => 'Pro3xInvoiceBundle:Location'))
			->add('name', 'text', array('label' => 'Naziv blagajne', 'required' => false))
            ->add('sequence', 'text', array('label' => 'Sljedeći račun'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\InvoiceBundle\Entity\Position'
        ));
    }

    public function getName()
    {
        return 'pro3x_invoicebundle_positiontype';
    }
}
