<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

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
    private $id;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $sequence;
	
	/**
	 * @ManyToOne(targetEntity="Location", inversedBy="positions")
	  */
	private $location;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $name;
	
	/**
	 * @OneToMany(targetEntity="Pro3x\SecurityBundle\Entity\User", mappedBy="position")
	  */
	private $users;
	
	function __construct()
	{
		$this->invoices = new \Doctrine\Common\Collections\ArrayCollection();
	}

	
	/**
	 * @OneToMany(targetEntity="Invoice", mappedBy="position")
	  */
	private $invoices;
	
	public function getUsers()
	{
		return $this->users;
	}

	public function setUsers($users)
	{
		$this->users = $users;
	}

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
	
	public function getDescription()
	{
		if($this->getName())
			return $this->getLocationName() . ' : ' . $this->getName();
		else
			return $this->getLocationName();
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
