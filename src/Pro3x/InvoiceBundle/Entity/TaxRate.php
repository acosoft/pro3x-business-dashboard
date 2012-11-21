<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxRate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\TaxRateRepository")
 */
class TaxRate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @ORM\Column(name="rate", type="decimal", scale=2)
     */
    private $rate;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TaxRate
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return TaxRate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }
}
