<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Pro3x\WarehouseBundle\Entity\Receipt;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;

/**
 * Client
 *
 * @ORM\Table(name="pro3x_clients")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\CustomerRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({"customer" = "Customer", "supplier" = "\Pro3x\WarehouseBundle\Entity\Supplier"})
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
	
	/**
	 * @OneToMany(targetEntity="\Pro3x\WarehouseBundle\Entity\Receipt", mappedBy="supplier")
	  */
	private $receipts;
	
	/**
	 * @OneToMany(targetEntity="CustomerRelation", mappedBy="owner")
	  */
	private $relations;
	
	/**
	 * @OneToMany(targetEntity="Note", mappedBy="customer")
	  */
	private $notes;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $accomodation;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $otherAccomodation;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $ownership;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $otherOwnership;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $message;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $warning;
	
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $birthday = null;
	
	public function getBirthday()
	{
		return $this->birthday;
	}

	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}

	public function getOtherAccomodation()
	{
		return $this->otherAccomodation;
	}

	public function setOtherAccomodation($otherAccomodation)
	{
		$this->otherAccomodation = $otherAccomodation;
	}

	public function getOtherOwnership()
	{
		return $this->otherOwnership;
	}

	public function setOtherOwnership($otherOwnership)
	{
		$this->otherOwnership = $otherOwnership;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function getWarning()
	{
		return $this->warning;
	}

	public function setWarning($warning)
	{
		$this->warning = $warning;
	}

	public function getAccomodation()
	{
		return $this->accomodation;
	}

	public function setAccomodation($accomodation)
	{
		$this->accomodation = $accomodation;
	}

	public function getOwnership()
	{
		return $this->ownership;
	}

	public function setOwnership($ownership)
	{
		$this->ownership = $ownership;
	}

	public function getRegistrationKeys()
	{
		return $this->registrationKeys;
	}

	public function getReceipts()
	{
		return $this->receipts;
	}

	public function getNotes()
	{
		return $this->notes;
	}

	public function getRelations()
	{
		return $this->relations;
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
