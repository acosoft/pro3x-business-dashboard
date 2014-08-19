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
                ->add('taxPayer', 'choice', array('choices' => array(
                        null => '',
                        true => 'U sustavu PDV-a',
                        false => 'Nije u sustavu PDV-a'
                    ), 'label' => 'Fiskalni model',
                    'required' => false)
                )
                ->add('companyName', 'text', array('label' => '1. linija', 'required' => false))
                ->add('companyTaxNumber', 'text', array('label' => 'OIB', 'required' => false))
                ->add('iban', 'text', array('label' => 'IBAN', 'required' => false))
                ->add('address', 'text', array('label' => '2. linija', 'required' => false))
                ->add('other', 'text', array('label' => '3. linija', 'required' => false))
                ->add('code', 'text', array('label' => 'Numerička oznaka', 'required' => false))
                ->add('display', 'text', array('label' => 'Interni naziv', 'required' => false))
                ->add('name', 'text', array('label' => 'Naziv lokacije'))
                ->add('logo', 'text', array('label' => 'Logo URL', 'required' => false))
                ->add('street', 'text', array('label' => 'Ulica', 'required' => false))
                ->add('houseNumber', 'text', array('label' => 'Kućni broj', 'required' => false))
                ->add('houseNumberExtension', 'text', array('label' => 'Dodatak kućnom broju', 'required' => false))
                ->add('postalCode', 'text', array('label' => 'Poštanski broj', 'required' => false))
                ->add('city', 'text', array('label' => 'Grad', 'required' => false))
                ->add('settlement', 'text', array('label' => 'Naselje', 'required' => false))
                ->add('workingHours', 'textarea', array('label' => 'Opis', 'required' => false))
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
