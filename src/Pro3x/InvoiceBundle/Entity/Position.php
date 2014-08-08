<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Position
 *
 * @ORM\Table(name="pro3x_positions")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\PositionRepository")
 */
class Position
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $sequence;
	
	/**
	 * @ManyToOne(targetEntity="Location", inversedBy="positions")
	  */
	protected $location;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	private $tenderSequence;
	
	function __construct()
	{
		$this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
		$this->tenderSequence = 1;
		$this->sequence = 1;
	}

	public function getTenderSequence()
	{
		return $this->tenderSequence;
	}

	public function setTenderSequence($tenderSequence)
	{
		$this->tenderSequence = $tenderSequence;
	}

	/**
	 * @OneToMany(targetEntity="Invoice", mappedBy="position")
	  */
	private $invoices;

	public function getInvoices()
	{
		return $this->invoices;
	}

	public function setInvoices($invoices)
	{
		$this->invoices = $invoices;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getSequence()
	{
		return $this->sequence;
	}

	public function setSequence($sequence)
	{
		$this->sequence = $sequence;
	}

	/**
	 * 
	 * @return Location
	 */
	public function getLocation()
	{
		return $this->location;
	}

	public function setLocation($location)
	{
		$this->location = $location;
	}
	
	public function getLocationName()
	{
		return $this->getLocation()->getName();
	}
        
        private function getLocationDisplayName()
        {
            if($this->getLocation()->getDisplay())
            {
                return $this->getLocation()->getDisplay();
            }
            else
            {
                return $this->getLocation()->getName();
            }
        }
	
	public function getDescription()
	{
		if($this->getName())
			return $this->getLocationDisplayName() . ' : ' . $this->getName();
		else
			return $this->getLocationDisplayName();
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
