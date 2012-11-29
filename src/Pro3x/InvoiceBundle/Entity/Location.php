<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Shop
 *
 * @ORM\Table(name="pro3x_locations")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\LocationRepository")
 */
class Location
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
	 * @OneToMany(targetEntity="Position", mappedBy="location")
	  */
	private $positions;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $name;

	public function getPositions()
	{
		return $this->positions;
	}

	public function setPositions($positions)
	{
		$this->positions = $positions;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
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
