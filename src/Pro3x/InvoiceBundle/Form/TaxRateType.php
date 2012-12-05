<?php

namespace Pro3x\InvoiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaxRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('taxGroup', 'choice', array('label' => 'Porezna grupa', 'choices' => array('Pdv' => 'Porez na dodanu vrijednost', 'Pnp' => 'Porez na potrošnju')))
            ->add('name', 'text', array('label' => 'Naziv porezne stope'))
            ->add('rate', 'text', array('label' => 'Iznos porezne stope'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\InvoiceBundle\Entity\TaxRate'
        ));
    }

    public function getName()
    {
        return 'pro3x_invoicebundle_taxratetype';
    }
}
