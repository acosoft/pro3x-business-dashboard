<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * InvoiceItemTax
 *
 * @ORM\Table(name="pro3x_invoice_item_taxes")
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
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $taxGroup;

	/**
	 * 
	 * @param TaxRate $taxRate
	 */
	function __construct($taxRate)
	{
		$this->taxId = $taxRate->getId();
		$this->taxDescription = $taxRate->getName();
		$this->taxRate = $taxRate->getRate();
		$this->taxGroup = $taxRate->getTaxGroup();
	}
	
	public function getTaxGroup()
	{
		return $this->taxGroup;
	}

	public function setTaxGroup($taxGroup)
	{
		$this->taxGroup = $taxGroup;
	}

	/**
	 * 
	 * @return InvoiceItem
	 */
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
	
	public function getTaxAmount()
	{
		return $this->getItem()->getTotalPrice() * $this->getTaxRate();
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
