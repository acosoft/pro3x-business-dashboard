<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Invoice
 *
 * @ORM\Table(name="pro3x_invoices")
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
	
	/**
	 * @ManyToOne(targetEntity="Pro3x\SecurityBundle\Entity\User", inversedBy="invoices")
	  */
	private $user;
		
	/**
	 * @var Position Point of Sale position at location
	 * @ManyToOne(targetEntity="Position", inversedBy="invoices")
	  */
	private $position;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $sequence;
	
	private $numeric;

	public function __construct()
	{
		$this->created = new \DateTime('now');
		$this->sequence = null;
	}

	public function getSequence()
	{
		return $this->sequence;
	}

	public function setSequence($sequence)
	{
		$this->sequence = $sequence;
	}
	
	public function getSequenceFormated()
	{
		if($this->getSequence() == null)
		{
			return '- - -';
		}
		else
		{
			return $this->getSequence();
		}
	}
	
	public function getDateTimeFormated()
	{
		return $this->created->format('d.m.Y H:i\h');
	}

	public function getDateFormated()
	{
		return $this->created->format('d.m.Y');
	}
	
	public function getTimeFormated()
	{
		return $this->created->format('H:i');
	}

	public function getPosition()
	{
		return $this->position;
	}

	public function setPosition($position)
	{
		$this->position = $position;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setUser($user)
	{
		$this->user = $user;
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
	
	private function getTaxItemsArray()
	{
		$taxes = array();
		
		foreach ($this->getItems() as $item) /* @var $item InvoiceItem */
		{
			foreach ($item->getTaxes() as $tax) /* @var $tax InvoiceItemTax */
			{
				$taxes[$tax->getTaxId()][] = $tax;
			}
		}
		
		return $taxes;
	}
	
	public function getTaxItems()
	{
		$taxes = $this->getTaxItemsArray();
		$aggTax = array();
		
		foreach($taxes as $key => $taxItems) /* @var $taxItems InvoiceItemTax[] */
		{
			$item = array();
			$item['base'] = 0;
			$item['amount'] = 0;
			$item['rate'] = $taxItems[0]->getTaxRate();
			$item['description'] = $taxItems[0]->getTaxDescription();
			$item['total'] = 0;
			
			foreach ($taxItems as $taxItem) /* @var $taxItem InvoiceItemTax */
			{
				$item['base'] += $taxItem->getItem()->getPrice();
				$item['amount'] += $taxItem->getTaxAmount();
				$item['total'] = $item['base'] + $item['amount'];
			}
			
			$aggTax[] = $item;
		}
		
		$nf = $this->getNumeric()->getNumberFormatter();
		foreach ($aggTax as &$item)
		{
			$item['base'] = $nf->format($item['base']);
			$item['total'] = $nf->format($item['total']);
			$item['amount'] = $nf->format($item['amount']);
			$item['rate'] = $nf->format($item['rate'] * 100);
		}
		
		return $aggTax;
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
	
	public function getTotalFormated()
	{
		return $this->getNumeric()->getNumberFormatter()->format($this->getTotal());
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
