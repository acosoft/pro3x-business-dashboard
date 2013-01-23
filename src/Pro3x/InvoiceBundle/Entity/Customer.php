<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Client
 *
 * @ORM\Table(name="pro3x_clients")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\CustomerRepository")
 */
class Customer
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
	 * @ORM\Column(type="string")
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $address;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $location;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $taxNumber;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $phone;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $cellPhone;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $email;
	
	/**
	 * @OneToMany(targetEntity="Invoice", mappedBy="customer")
	  */
	private $invoices;
	
	/**
	 * @OneToMany(targetEntity="\Pro3x\RegistrationKeysBundle\Entity\RegistrationKey", mappedBy="customer")
	  */
	private $registrationKeys;
	
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

	public function getAddress()
	{
		return $this->address;
	}

	public function setAddress($address)
	{
		$this->address = $address;
	}

	public function getLocation()
	{
		return $this->location;
	}

	public function setLocation($location)
	{
		$this->location = $location;
	}

	public function getTaxNumber()
	{
		return $this->taxNumber;
	}

	public function setTaxNumber($taxNumber)
	{
		$this->taxNumber = $taxNumber;
	}

	public function getPhone()
	{
		return $this->phone;
	}

	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	public function getCellPhone()
	{
		return $this->cellPhone;
	}

	public function setCellPhone($cellPhone)
	{
		$this->cellPhone = $cellPhone;
	}
	
	public function getPhones()
	{
		$phones = array();
		
		if($this->getPhone()) $phones[] = $this->getPhone ();
		if($this->getCellPhone()) $phones[] = $this->getCellPhone ();
		
		return $phones;
	}
	
	public function getDescription()
	{
		$description = array();
		$description[] = $this->getName();
		
		if($this->getAddress()) $description[] = $this->getAddress ();
		if($this->getLocation()) $description[] = $this->getLocation ();

		return $description;
	}
	
	public function getDescriptionFormated()
	{
		return implode(", ", $this->getDescription());
	}
	
	public function getAddressDescription()
	{
		$description = array();
		
		if($this->getAddress()) $description[] = $this->getAddress ();
		if($this->getLocation()) $description[] = $this->getLocation ();
		
		return $description;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
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
