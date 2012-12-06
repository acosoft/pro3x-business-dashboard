<?php

namespace Pro3x\InvoiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('location', 'entity', array(
				'label' => 'Lokacija', 
				'expanded' => false, 'property' => 'name', 
				'multiple' => false, 
				'class' => 'Pro3xInvoiceBundle:Location'))
			->add('transactionType', 'choice', array('choices' => array('G' => 'Gotovina', 'K' => 'Kartice', 'C' => 'Čekovi', 'T' => 'Virman', 'O' => 'Ostalo'), 'label' => 'Način plaćanja'))
			->add('useGoogleCloud', 'choice', array('label' => 'Način ispisa', 'choices' => array(true => 'Google Cloud Ispis', false => 'Direktni ispis')))
            ->add('name', 'text', array('label' => 'Naziv'))
            ->add('filename', 'text', array('label' => 'Datoteka'))
			
        ;
    }
	
	public static function getTransactionTypes()
	{
		return array('G' => 'Gotovina', 'K' => 'Kartice', 'C' => 'Čekovi', 'T' => 'Virman', 'O'=> 'Ostalo');
	}		
	
	public static function formatTransactionType($type)
	{
		$types = self::getTransactionTypes();
		return $types[$type];
	}

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\InvoiceBundle\Entity\Template'
        ));
    }

    public function getName()
    {
        return 'pro3x_invoicebundle_templatetype';
    }
}
