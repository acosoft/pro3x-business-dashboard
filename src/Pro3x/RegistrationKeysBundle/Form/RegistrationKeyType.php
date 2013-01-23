<?php

namespace Pro3x\RegistrationKeysBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationKeyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', 'entity', 
					array('label' => 'Proizvod', 
						'expanded' => false, 
						'multiple' => false,
						'property' => 'name',
						'class' => 'Pro3xInvoiceBundle:Product'))
            ->add('customer', 'entity', 
					array('label' => "Kupac", 
						'expanded' => false, 
						'multiple' => false,
						'property' => "descriptionFormated",
						'class' => 'Pro3xInvoiceBundle:Customer'))
			->add('validFrom', 'datetime', array(
				'input' => 'datetime',
				'widget' => 'single_text',
				'format' => 'dd.MM.yyyy',
				'label' => 'Vrijedi od'
			))
			->add('validTo', 'datetime', array(
				'input' => 'datetime',
				'widget' => 'single_text',
				'format' => 'dd.MM.yyyy',
				'label' => 'Vrijedi do'
			))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\RegistrationKeysBundle\Entity\RegistrationKey'
        ));
    }

    public function getName()
    {
        return 'pro3x_registrationkeysbundle_registrationkeytype';
    }
}
