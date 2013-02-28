<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Invoice
 *
 * @ORM\Table(name="pro3x_invoices")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\InvoiceRepository")
 * @ORM\HasLifecycleCallbacks
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
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $tenderDate;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $sequence;
	
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $tenderSequence;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $uuid;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $companyTaxNumber;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $uniqueInvoiceNumber;
	
	/**
	 * @ManyToOne(targetEntity="Template", inversedBy="invoices")
	  */
	private $template;
	
	/**
	 * @ManyToOne(targetEntity="Template", inversedBy="tenders")
	  */
	private $tenderTemplate;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $invoiceTotal;

	/**
	 * @ManyToOne(targetEntity="DailySalesReport", inversedBy="invoices")
	  */
	private $dailyReport;
	
	public function getTenderTemplate()
	{
		return $this->tenderTemplate;
	}

	public function setTenderTemplate($tenderTemplate)
	{
		$this->tenderTemplate = $tenderTemplate;
	}

	public function getDailyReport()
	{
		return $this->dailyReport;
	}

	public function setDailyReport($report)
	{
		$this->dailyReport = $report;
	}

	private $numeric;
	
	public function getTenderSequence()
	{
		return $this->tenderSequence;
	}

	public function setTenderSequence($tenderSequence)
	{
		$this->tenderSequence = $tenderSequence;
	}

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isFiscalTransaction;

	public function __construct()
	{
		$this->created = new \DateTime('now');
		
		$this->sequence = null;
		$this->invoiceTotal = 0;
		$this->items = new ArrayCollection();
		$this->locked = false;
		
		$this->isFiscalTransaction = false;
	}
	
	public function hasCustomerMessage()
	{
		if($this->getCustomer() != null && $this->getCustomer()->getMessage() != '')
		{
			return true;
		}
		
		return false;
	}
	
	public function hasCustomerWarning()
	{
		if($this->getCustomer() != null && $this->getCustomer()->getWarning() != '')
			return true;
		else
			return false;
	}
	
	public function getTenderDate()
	{
		return $this->tenderDate;
	}

	public function setTenderDate($tenderDate)
	{
		$this->tenderDate = $tenderDate;
	}

	public function isFiscalTransaction()
	{
		return $this->isFiscalTransaction;
	}

	public function setFiscalTransaction($isFiscalTransaction)
	{
		$this->isFiscalTransaction = $isFiscalTransaction;
	}

	public function isInvalid()
	{
		if($this->getSequence() != null && $this->isFiscalTransaction() == true && $this->getUniqueInvoiceNumber() == null)
			return true;
		else
			return false;
	}
	
	public function getInvoiceTotal()
	{
		return $this->invoiceTotal;
	}

	public function setInvoiceTotal($invoiceTotal)
	{
		$this->invoiceTotal = $invoiceTotal;
	}
	
	public function calculate()
	{
		$this->setInvoiceTotal($this->getTotal());
	}

	/**
	 * 
	 * @return Template
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	public function setTemplate($template)
	{
		$this->template = $template;
	}

	public function getUniqueInvoiceNumber()
	{
		return $this->uniqueInvoiceNumber;
	}
	
	public function getUniqueInvoiceNumberFormated()
	{
		if($this->uniqueInvoiceNumber)
			return $this->uniqueInvoiceNumber;
		else
			return 'Nedostupan';
	}

	public function setUniqueInvoiceNumber($uniqueInvoiceNumber)
	{
		$this->uniqueInvoiceNumber = $uniqueInvoiceNumber;
	}

	public function getCompanyTaxNumber()
	{
		return $this->companyTaxNumber;
	}

	public function setCompanyTaxNumber($companyTaxNumber)
	{
		$this->companyTaxNumber = $companyTaxNumber;
	}

	public function getSequence()
	{
		return $this->sequence;
	}

	public function setSequence($sequence)
	{
		$this->sequence = $sequence;
	}
	
	public function getCreated()
	{
		return $this->created;
	}

	public function setCreated($created)
	{
		$this->created = $created;
	}

	public function getUuid()
	{
		return $this->uuid;
	}

	public function setUuid($uuid)
	{
		$this->uuid = $uuid;
	}

	public function getSequenceFormated()
	{
		if($this->getSequence() == null)
		{
			if($this->getTenderSequence() == null)
			{
				return 'skica';
			}
			else
			{
				return 'ponuda | ' . $this->getTenderSequence();
			}
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

	/**
	 * 
	 * @param Position $position
	 */
	public function setPosition($position)
	{
		$this->position = $position;
		
		$this->setCompanyTaxNumber($position->getLocation()->getCompanyTaxNumber());
	}

	/**
	 * 
	 * @return \Pro3x\SecurityBundle\Entity\User
	 */
	public function getUser()
	{
		return $this->user;
	}

	public function setUser($user)
	{
		$this->user = $user;
	}
	
	public function getUserDisplayName()
	{
		return $this->getUser()->getDisplayName();
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
			$item['group'] = $taxItems[0]->getTaxGroup();
			
			foreach ($taxItems as $taxItem) /* @var $taxItem InvoiceItemTax */
			{
				$item['base'] += $taxItem->getItem()->getTotalPrice();
				$item['amount'] += $taxItem->getTaxAmount();
				$item['total'] = $item['base'] + $item['amount'];
			}
			
			$aggTax[] = $item;
		}
		
		$nf = $this->getNumeric()->getNumberFormatter();
		foreach ($aggTax as &$item)
		{
			$item['baseNumeric'] = $item['base'];
			$item['base'] = $nf->format($item['base']);
			
			$item['totalNumeric'] = $item['total'];
			$item['total'] = $nf->format($item['total']);
			
			$item['amountNumeric'] = $item['amount'];
			$item['amount'] = $nf->format($item['amount']);
			
			$item['rateNumeric'] = $item['rate'] * 100;
			$item['rate'] = $nf->format($item['rate'] * 100);
		}
		
		return $aggTax;
	}
	
	public function getTotal()
	{
		$total = 0;
		
		foreach ($this->getItems() as $item) /* @var $item InvoiceItem */
		{
			$total += $item->getDicountPrice();
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
