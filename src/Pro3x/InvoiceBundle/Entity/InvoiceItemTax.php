<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * InvoiceItemTax
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\InvoiceItemTaxRepository")
 */
class InvoiceItemTax
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
	 * @ORM\Column(type="integer")
	 */
	private $taxId;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $taxDescription;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $taxRate;
	
	/**
	 * @ManyToOne(targetEntity="InvoiceItem", inversedBy="taxes")
	  */
	private $item;

	
	function __construct($taxRate)
	{
		$this->taxId = $taxRate->getId();
		$this->taxDescription = $taxRate->getName();
		$this->taxRate = $taxRate->getRate();
	}
	
	public function getItem()
	{
		return $this->item;
	}

	public function setItem($item)
	{
		$this->item = $item;
	}

	public function getTaxId()
	{
		return $this->taxId;
	}

	public function setTaxId($taxId)
	{
		$this->taxId = $taxId;
	}

	public function getTaxDescription()
	{
		return $this->taxDescription;
	}

	public function setTaxDescription($taxDescription)
	{
		$this->taxDescription = $taxDescription;
	}

	public function getTaxRate()
	{
		return $this->taxRate;
	}

	public function setTaxRate($taxRate)
	{
		$this->taxRate = $taxRate;
	}

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
