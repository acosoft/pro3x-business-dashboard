<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

/**
 * InvoiceItem
 *
 * @ORM\Table(name="pro3x_invoice_items")
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
	private $taxedPrice;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $totalTaxedPrice;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $dicountPrice;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $discountAmount;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $totalPrice;

	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $discount;
	
	/**
	 * @OneToMany(targetEntity="InvoiceItemTax", mappedBy="item", cascade={"all"}, fetch="EAGER")
	  */
	private $taxes;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $taxAmount;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $totalTaxAmount;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $unit;
	
	/**
	 * @ManyToOne(targetEntity="Invoice", inversedBy="items")
	  */
	private $invoice;
	
	private $numeric;
	
	public function getTotalTaxAmount()
	{
		return $this->totalTaxAmount;
	}

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

	public function getTotalPrice()
	{
		return $this->totalPrice;
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

	/**
	 * 
	 * @return invoice
	 */
	public function getInvoice()
	{
		return $this->invoice;
	}
	
	/**
	 * @PrePersist
	 * @PreUpate
	 */
	public function calculate()
	{
		$this->taxAmount = $this->getUnitPrice() * $this->getTaxRate();
		$this->taxedPrice = round($this->getUnitPrice() + $this->getTaxAmount(), 2);
		
		$this->totalTaxedPrice = round($this->getTaxedPrice() * $this->getAmount(), 2);
		$this->discountAmount = round($this->getTotalTaxedPrice() * $this->getDiscount(), 2);
		$this->dicountPrice = round($this->getTotalTaxedPrice() - $this->getDiscountAmount(), 2);

		$this->totalPrice = round($this->getDicountPrice() / (1 + $this->getTaxRate()), 2);
		$this->totalTaxAmount = round($this->getTotalTaxedPrice() - $this->getTotalPrice(), 2);
	}
	
	public function getTotalTaxedPrice()
	{
		return $this->totalTaxedPrice;
	}

	public function getDicountPrice()
	{
		return $this->dicountPrice;
	}

	public function getDiscountAmount()
	{
		return $this->discountAmount;
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
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getDiscount() * 100);
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
		$rate = 0;
		
		foreach($this->getTaxes() as $tax) /* @var $tax InvoiceItemTax */
		{
			 $rate += $tax->getTaxRate();
		}
		
		return $rate;
	}
	
	public function getTaxAmount()
	{
		return $this->taxAmount;
	}

	public function getTaxAmountFormated()
	{
		return $this->getNumeric()->getNumberFormatter()->format($this->getTaxAmount());
	}

	public function getTaxedPrice()
	{
		return $this->taxedPrice;
	}
	
	public function getTaxedPriceFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getTaxedPrice());
	}
	
	public function getTotalTaxedPriceFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getTotalTaxedPrice());
	}
	
	public function getDiscountPriceFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getDicountPrice());
	}
}
