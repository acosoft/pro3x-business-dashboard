<?php

namespace Pro3x\WarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use \Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Receipt
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pro3x\WarehouseBundle\Entity\ReceiptRepository")
 */
class Receipt
{
	/**
	 * 
	 * @param \Pro3x\InvoiceBundle\Entity\Customer $supplier
	 */
	function __construct($supplier)
	{
		$this->created = new \DateTime('now');
		$this->items = new ArrayCollection();
		$this->supplier = $supplier;
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
	 * @ManyToOne(targetEntity="\Pro3x\InvoiceBundle\Entity\Customer", inversedBy="receipts")
	 * 
	 */
	private $supplier;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;
	
	/**
	 * @OneToMany(targetEntity="ReceiptItem", mappedBy="receipt")
	 * @var ArrayCollection 
	  */
	private $items;
	
	public function addItem($product, $amount, $dicount = 0)
	{
		$this->items->add(new ReceiptItem($this, $product, $amount, $dicount));
	}
	
	public function getSupplier()
	{
		return $this->supplier;
	}

	public function getCreated()
	{
		return $this->created;
	}

	public function getItems()
	{
		return $this->items;
	}

	
	public function getTotal()
	{
		$total = 0;
		
		foreach ($this->items as $item) /* @var $item ReceiptItem */
		{
			$total += $item->getTotalTaxedDiscountPrice();
		}
		
		return round($total, 2);
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
