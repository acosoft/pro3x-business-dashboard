<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * DailySalesReport
 *
 * @ORM\Table(name="pro3x_daily_sales_report")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\DailySalesReportRepository")
 */
class DailySalesReport
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
	 * @ManyToOne(targetEntity="Pro3x\SecurityBundle\Entity\User")
	  */
	private $operator;
	
	/**
	 * @ORM\Column(type="decimal", scale=2)
	 */
	private $total;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;
	
	/**
	 * @ManyToOne(targetEntity="Position")
	  */
	private $position;
	
	/**
	 * @OneToMany(targetEntity="Invoice", mappedBy="dailyReport")
	  */
	private $invoices;
	
	function __construct()
	{
		$this->created = new \DateTime();
	}

	public function getPosition()
	{
		return $this->position;
	}

	public function setPosition($position)
	{
		$this->position = $position;
	}

	public function getCreated()
	{
		return $this->created;
	}

	public function setCreated($created)
	{
		$this->created = $created;
	}

	public function getOperator()
	{
		return $this->operator;
	}

	public function setOperator($operator)
	{
		$this->operator = $operator;
		$this->setPosition($operator->getPosition());
	}

	public function getTotal()
	{
		return $this->total;
	}

	public function setTotal($total)
	{
		$this->total = $total;
	}

	public function getInvoices()
	{
		return $this->invoices;
	}

	public function setInvoices($invoices)
	{
		$this->invoices = $invoices;
	}
	
	public function calculateTotal()
	{
		$this->total = 0;
		
		foreach ($this->getInvoices() as $invoice) /* @var $invoice Invoice */
		{
			$this->total += $invoice->getInvoiceTotal();
		}
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
