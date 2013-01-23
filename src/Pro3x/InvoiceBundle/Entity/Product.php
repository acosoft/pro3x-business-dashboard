<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Product
 *
 * @ORM\Table(name="pro3x_products")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\ProductRepository")
 */
class Product
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
	private $name;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $barcode;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $code;
	
	/**
	 * @ORM\Column(type="decimal", precision=14, scale=6)
	 */
	private $unitPrice;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $unit;

	/**
	 * @OneToMany(targetEntity="\Pro3x\RegistrationKeysBundle\Entity\RegistrationKey", mappedBy="product")
	  */
	private $registrationKeys;
	
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
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="TaxRate", inversedBy="products")
	 * @ORM\JoinTable(name="pro3x_product_tax_rates")
	 */
	private $taxRates;

	function __construct()
	{
		$this->unitPrice = 0;
		$this->taxRates = new ArrayCollection();
		$this->unit = "kom";
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
	
	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function getBarcode()
	{
		return $this->barcode;
	}

	public function setBarcode($barcode)
	{
		$this->barcode = $barcode;
		return $this;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function setCode($code)
	{
		$this->code = $code;
		return $this;
	}

	public function getUnitPrice()
	{
		return $this->getTaxedPrice() / (1 + $this->getTotalTaxRate());
	}
	
	public function getUnitPriceFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getUnitPrice());
	}

	public function setUnitPrice($unitPrice)
	{
		$this->unitPrice = $unitPrice;
		return $this;
	}
	
	public function getTaxAmountFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getTaxAmount());
	}
	
	public function getTaxAmount()
	{
		return $this->getTaxedPrice() - $this->getUnitPrice();
	}
	
	public function getTaxedPriceFormated()
	{
		return $this->getNumeric()->getNumberFormatter(2)->format($this->getTaxedPrice());
	}
	
	public function getTotalTaxRate()
	{
		$totalRate = 0;
		
		foreach ($this->getTaxRates() as $tax) /* @var $tax TaxRate */
		{
			$totalRate += $tax->getRate();
		}
		
		return $totalRate;
	}
	
	public function getTaxedPrice()
	{
		
		return round($this->unitPrice * (1 + $this->getTotalTaxRate()), 2);
	}

	public function getUnit()
	{
		return $this->unit;
	}

	public function setUnit($unit)
	{
		$this->unit = $unit;
		return $this;
	}

	public function getTaxRates()
	{
		return $this->taxRates;
	}

	public function setTaxRates($taxRates)
	{
		$this->taxRates = $taxRates;
		return $this;
	}

}
