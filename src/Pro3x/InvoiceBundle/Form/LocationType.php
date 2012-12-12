<?php

namespace Pro3x\InvoiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('taxPayer', 'choice', array('choices' => array(true => 'U sustavu PDV-a', false => 'Nije u sustavu PDV-a'), 'label' => 'Obračun poreza'))
			->add('companyTaxNumber', 'text', array('label' => 'OIB Tvrtke'))
            ->add('name', 'text', array('label' => 'Naziv lokacije'))
			->add('street', 'text', array('label' => 'Ulica', 'required' => false))
			->add('houseNumber', 'text', array('label' => 'Kućni broj', 'required' => false))
			->add('houseNumberExtension', 'text', array('label' => 'Dodatak kućnom broju', 'required' => false))
			->add('postalCode', 'text', array('label' => 'Poštanski broj', 'required' => false))
			->add('city', 'text', array('label' => 'Grad', 'required' => false))
			->add('settlement', 'text', array('label' => 'Naselje', 'required' => false))
			->add('workingHours', 'text', array('label' => 'Radno vrijeme', 'required' => false))
			->add('securityKey', 'textarea', array('label' => 'Sigurnosni ključ', 'required' => false))
			->add('securityCertificate', 'textarea', array('label' => 'Sigurnosni certifikat', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\InvoiceBundle\Entity\Location'
        ));
    }

    public function getName()
    {
        return 'pro3x_invoicebundle_locationtype';
    }
}
