<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * InvoiceItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\InvoiceItemRepository")
 */
class InvoiceItem
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
	 * @ORM\Column(type="string")
	 */
	private $description;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $unitPrice;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $amount;
	

	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $discount;
	
	/**
	 * @OneToMany(targetEntity="InvoiceItemTax", mappedBy="item", cascade={"all"}, fetch="EAGER")
	  */
	private $taxes;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $unit;
	
	/**
	 * @ManyToOne(targetEntity="Invoice", inversedBy="items")
	  */
	private $invoice;
	
	private $numeric;

	/**
	 * 
	 * @return \Pro3x\Online\Numeric
	 */
	public function getNumeric()
	{
		return $this->numeric;
	}

	public function setNumeric($numeric)
	{
		$this->numeric = $numeric;
	}

	/**
	 * 
	 * @param Product $product
	 */
	public function __construct($product)
	{
		$this->setDescription($product->getName());
		$this->setUnitPrice($product->getUnitPrice());
		$this->setUnit($product->getUnit());
		$this->setAmount(1);
		$this->setDiscount(0);
		
		$this->taxes = new ArrayCollection();
		
		foreach($product->getTaxRates() as $taxRate)
		{
			$this->taxes->add($tax = new InvoiceItemTax($taxRate));
			$tax->setItem($this);
		}	
	}

	public function getInvoice()
	{
		return $this->invoice;
	}

	public function setInvoice($invoice)
	{
		$this->invoice = $invoice;
	}

	public function getUnit()
	{
		return $this->unit;
	}

	public function setUnit($unit)
	{
		$this->unit = $unit;
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
	
	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($description)
	{
		$this->description = $description;
	}

	public function getUnitPrice()
	{
		return $this->unitPrice;
	}
	
	public function getUnitPriceFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getUnitPrice());
	}

	public function setUnitPrice($unitPrice)
	{
		$this->unitPrice = $unitPrice;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function setAmount($amount)
	{
		$this->amount = $amount;
	}
	
	public function getAmountFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getAmount());
	}

	public function getDiscount()
	{
		return $this->discount;
	}

	public function setDiscount($discount)
	{
		$this->discount = $discount;
	}
	
	public function getDiscountFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getDiscount());
	}

	public function getTaxes()
	{
		return $this->taxes;
	}

	public function setTaxes($taxes)
	{
		$this->taxes = $taxes;
	}
	
	public function getTaxRate()
	{
		$rate = 1;
		
		foreach($this->getTaxes() as $tax) /* @var $tax InvoiceItemTax */
		{
			 $rate += $tax->getTaxRate();
		}
		
		return $rate;
	}
	
	public function getTaxedPrice()
	{
		return $this->getPrice() * $this->getTaxRate();
	}
	
	public function getTaxedPriceFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getTaxedPrice());
	}

	public function getPrice()
	{
		return $this->getUnitPrice() * $this->getAmount() * (1 - $this->getDiscount());
	}
	
	public function getPriceFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getPrice());
	}
}
