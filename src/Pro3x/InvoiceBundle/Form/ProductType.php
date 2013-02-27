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
			->add('inputTaxRate', 'entity', array(
				'label' => 'Porez u nabavi', 
				'expanded' => false, 'property' => 'display', 
				'multiple' => false, 
				'required' => false,
				'class' => 'Pro3xInvoiceBundle:TaxRate'))	
            ->add('unitPrice', 'number', array('precision' => 6, 'label' => 'Jedinična cijena'))
			->add('taxRates', 'entity', array(
				'label' => 'Porezne stope', 
				'expanded' => true, 'property' => 'display', 
				'multiple' => true, 
				'class' => 'Pro3xInvoiceBundle:TaxRate', 
				'attr' => array('class' => 'pro3x_checkbox_list')))
			->add('taxAmount', 'number', array('mapped' => false, 'label' => 'Iznos poreza', 'read_only' => true))
			->add('taxedPrice', 'number', array('precision' => 2, 'mapped' => false, 'label' => 'Prodajna cijena'))
			->add('taxedInputPrice', 'number', array('precision' => 2,'label' => 'Nabavna cijena'))
			->add('inputPrice', 'number', array('precision' => 6, 'label' => 'Nabavna cijena'))
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
