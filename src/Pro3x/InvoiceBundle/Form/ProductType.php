<?php

namespace Pro3x\InvoiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Naziv artikla'))
            ->add('barcode', 'text', array('label' => 'Barkod'))
            ->add('code', 'text', array('label' => 'Interna oznaka'))
            ->add('unitPrice', 'text', array('label' => 'Jedinična cijena'))
			->add('taxRates', 'entity', array(
				'label' => 'Porezne stope', 
				'expanded' => true, 'property' => 'name', 
				'multiple' => true, 
				'class' => 'Pro3xInvoiceBundle:TaxRate', 
				'attr' => array('class' => 'pro3x_checkbox_list')))
			->add('taxAmount', 'text', array('mapped' => false, 'label' => 'Iznos poreza', 'read_only' => true))
			->add('taxedPrice', 'text', array('mapped' => false, 'label' => 'Ukupna cijena'))
            ->add('unit', 'text', array('label' => 'Mjerna jedinica'))
            
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\InvoiceBundle\Entity\Product'
        ));
    }

    public function getName()
    {
        return 'pro3x_invoicebundle_producttype';
    }
}
