<?php

namespace Pro3x\WarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * ReceiptItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pro3x\WarehouseBundle\Entity\ReceiptItemRepository")
 */
class ReceiptItem
{
	/**
	 * 
	 * @param \Pro3x\InvoiceBundle\Entity\Product $product
	 * @param decimal(14, 2) $amount
	 * @param decimal(14, 2) $discount percentage in normalized form, for example 0.25 for 25%
	 * @param Receipt $receipt;
	 */
	public function __construct($receipt, $product, $amount, $discount = 0)
	{
		$this->receipt = $receipt;
		$this->product = $product;
		$this->amount = $amount;
		
		$this->description = $this->product->getName();
		
		$this->inputTaxRate = $this->product->getInputTaxRate();
		$this->taxedInputPrice = $product->getTaxedInputPrice();
		
		$this->amount = $amount;
		
		$this->totalTaxedPrice = $this->taxedInputPrice * $this->amount;
		
		$this->discount = $discount;
		$this->discountAmount = $this->totalTaxedPrice * $this->discount;
		
		$this->totalTaxedDiscountPrice = $this->totalTaxedPrice - $this->discountAmount;
		$this->totalTaxAmount = $this->totalTaxedDiscountPrice * $this->inputTaxRate->getRate();
		
		$this->totalDiscountPrice = $this->totalTaxedDiscountPrice - $this->totalTaxAmount;
	}
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
	 * @ManyToOne(targetEntity="Receipt", inversedBy="items")
	  */
	private $receipt;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=2)
	 */
	private $taxedInputPrice;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=2)
	 */
	private $amount;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=2)
	 */
	private $totalTaxedPrice;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=2)
	 */
	private $discount;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=2)
	 */
	private $discountAmount;
	
	/**
	 * @ManyToOne(targetEntity="\Pro3x\InvoiceBundle\Entity\TaxRate")
	  */
	private $inputTaxRate;
	
	/**
	 * @ManyToOne(targetEntity="\Pro3x\InvoiceBundle\Entity\Product", inversedBy="receiptItems")
	  */
	private $product;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=2)
	 */
	private $totalTaxedDiscountPrice;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=2)
	 */
	private $totalTaxAmount;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=2)
	 */
	private $totalDiscountPrice;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $description;
	
	public function getReceipt()
	{
		return $this->receipt;
	}

	public function getTaxedInputPrice()
	{
		return $this->taxedInputPrice;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function getTotalTaxedPrice()
	{
		return $this->totalTaxedPrice;
	}

	public function getDiscount()
	{
		return $this->discount;
	}

	public function getDiscountAmount()
	{
		return $this->discountAmount;
	}

	public function getInputTaxRate()
	{
		return $this->inputTaxRate;
	}

	public function getProduct()
	{
		return $this->product;
	}

	public function getTotalTaxedDiscountPrice()
	{
		return $this->totalTaxedDiscountPrice;
	}
	
	public function getTotalTaxAmount()
	{
		return $this->totalTaxAmount;
	}

	public function getTotalDiscountPrice()
	{
		return $this->totalDiscountPrice;
	}

	public function getDescription()
	{
		return $this->description;
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
