<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Invoice
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\InvoiceRepository")
 */
class Invoice
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
	 * @OneToMany(targetEntity="InvoiceItem", mappedBy="invoice", cascade={"all"})
	  */
	private $items;

	/**
	 * @ManyToOne(targetEntity="Customer", inversedBy="invoices")
	  */
	private $customer;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $status;
	
	private $numeric;
	
	public function getNumeric()
	{
		return $this->numeric;
	}

	public function setNumeric($numeric)
	{
		$this->numeric = $numeric;
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
	
	/**
	 * 
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getItems()
	{
		if($this->getNumeric())
		{
			foreach ($this->items as $item) /* @var $item InvoiceItem */
			{
				$item->setNumeric($this->getNumeric());
			}
		}
		
		return $this->items;
	}

	public function setItems($items)
	{
		$this->items = $items;
	}

	/**
	 * 
	 * @return Customer
	 */
	public function getCustomer()
	{
		return $this->customer;
	}

	public function setCustomer($customer)
	{
		$this->customer = $customer;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
	
	public function getTotal()
	{
		$total = 0;
		
		foreach ($this->getItems() as $item) /* @var $item InvoiceItem */
		{
			$total += $item->getTaxedPrice();
		}
		
		return $total;
	}
	
	public function __call($name, $arguments)
	{
		if($name == 'customer.name' && $this->getCustomer() != null)
		{
			return implode(', ', $this->getCustomer()->getDescription());
		}
	}
	
	public function __get($name)
	{
		return "";
	}
}
