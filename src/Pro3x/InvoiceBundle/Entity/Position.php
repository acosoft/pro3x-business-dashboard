<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Position
 *
 * @ORM\Table()
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
	 * @ORM\Column(type="string")
	 */
	private $name;
	
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
