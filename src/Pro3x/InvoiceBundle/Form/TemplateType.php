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
				'expanded' => false, 'property' => 'display', 
				'multiple' => false, 
				'class' => 'Pro3xInvoiceBundle:Location'))
			->add('transactionType', 'choice', array('choices' => self::getTransactionTypes(), 'label' => 'Vrsta predloška'))
			->add('useGoogleCloud', 'hidden', array('data' => 0))
            ->add('name', 'text', array('label' => 'Naziv'))
            ->add('description', 'text', array('label' => 'Opis dokumenta'))
            ->add('paymentMethod', 'text', array('label' => 'Način plaćanja'))
            ->add('dueDays', 'choice', array('required' => false, 'label' => 'Rok plaćanja', 'choices' => array(
                null => 'Bez odgode',
                '+7 days' => '7 dana',
                '+15 days' => '15 dana',
                '+1 month' => '30 dana',
                '+2 month' => '60 dana',
                '+3 month' => '90 dana')))
            ->add('priority', 'text', array('label' => 'Prioritet'))
            ->add('background', 'choice', array('label' => 'Izgled dokumenta', 'choices' => array(
                ''        => '',
                'rgb(249, 249, 249)' => 'Siva pozadina',
                '#dff0d8' => 'Zelena pozadina',
                '#fcf8e3' => 'Žuta pozadina',
                '#d9edf7' => 'Plava pozadina',
                '#f2dede' => 'Crvena pozadina')))
            ->add('filename', 'text', array('label' => 'Predlozak', 'required' => false))
        ;
    }
	
    public static function getTransactionTypes()
    {
            return array('G' => 'Gotovina', 'K' => 'Kartice', 'C' => 'Čekovi', 'T' => 'Virman', 'P' => 'Ponuda', 'O' => 'Ostalo');
    }		

    public static function isTenderTransaction($transactionType)
    {
            if($transactionType == 'P')
            {
                    return true;
            }
            else
            {
                    return false;
            }
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
