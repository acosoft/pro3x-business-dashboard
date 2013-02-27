<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * Note
 *
 * @ORM\Table(name="pro3x_customer_notes")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\NoteRepository")
 */
class Note
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	function __construct()
	{
		$this->created = new \DateTime('now');
	}
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created;
	
	/**
	 * @ManyToOne(targetEntity="Customer", inversedBy="notes")
	  */
	private $customer;
	
	/**
	 * @ORM\Column(type="string")
	 */
	private $content;

	public function getCreated()
	{
		return $this->created;
	}

	public function setCreated($created)
	{
		$this->created = $created;
	}

	public function getCustomer()
	{
		return $this->customer;
	}

	public function setCustomer($customer)
	{
		$this->customer = $customer;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setContent($content)
	{
		$this->content = $content;
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
