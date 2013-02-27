<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * CustomerRelation
 *
 * @ORM\Table(name="pro3x_customer_relations")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\CustomerRelationRepository")
 */
class CustomerRelation
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
	 * @ManyToOne(targetEntity="Customer", inversedBy="relations")
	  */
	private $owner;
	
	/**
	 * @ManyToOne(targetEntity="RelationType")
	  */
	private $relationType;
	
	/**
	 * @ManyToOne(targetEntity="Customer")
	  */
	private $related;
	
	
	public function getOwner()
	{
		return $this->owner;
	}

	public function setOwner($owner)
	{
		$this->owner = $owner;
	}

	public function getRelationType()
	{
		return $this->relationType;
	}

	public function setRelationType($relationType)
	{
		$this->relationType = $relationType;
	}

	public function getRelated()
	{
		return $this->related;
	}

	public function setRelated($related)
	{
		$this->related = $related;
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
