<?php

namespace Pro3x\InvoiceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Naziv'))
            ->add('address', 'text', array('label' => 'Adresa', 'required' => false))
            ->add('location', 'text', array('label' => 'Lokacija', 'required' => false))
            ->add('taxNumber', 'text', array('label' => 'OIB', 'required' => false))
			->add('birthday', 'date', array('empty_value' => 'blank', 'widget' => 'single_text', 'format' => 'dd.MM.yyyy', 'label' => 'Datum rođenja', 'required' => false, 'attr' => array('class' => 'pro3x_date')))
            ->add('phone', 'text', array('label' => 'Telefon', 'required' => false))
            ->add('cellPhone', 'text', array('label' => 'Mobitel', 'required' => false))
            ->add('email', 'text', array('label' => 'Email', 'required' => false))
			->add('accomodation', 'choice', array('required' => false, 'choices' => array('kuca' => 'Kuća', 'stan' => 'Stan', 'baraka' => 'Baraka', 'beskucnik' => 'Beskućnik', 'ostalo' => 'Ostalo')))
            ->add('otherAccomodation', 'text', array('label' => 'Ostali smještaj', 'required' => false))
			->add('ownership', 'choice', array('required' => false, 'choices' => array('vlasnik' => 'Vlasnik', 'najam' => 'Najam', 'najam-grad' => 'Najam / Grad', 'kod-roditelja' => 'Kod roditelja', 'ostalo' => 'Ostalo')))				
            ->add('otherOwnership', 'text', array('label' => 'Ostalo vlasništvo', 'required' => false))
            ->add('message', 'textarea', array('label' => 'Poruka', 'required' => false))
            ->add('warning', 'textarea', array('label' => 'Upozorenje', 'required' => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pro3x\InvoiceBundle\Entity\Customer'
        ));
    }

    public function getName()
    {
        return 'pro3x_invoicebundle_customertype';
    }
}
