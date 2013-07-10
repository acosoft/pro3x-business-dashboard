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
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $companyTaxNumber;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $postalCode;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $houseNumber;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $houseNumberExtension;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $settlement;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $city;
	
	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $street;
	
	/**
	 * @ORM\Column(type="string", length=500, nullable=true)
	 */
	private $workingHours;
	
	/**
	 * @OneToMany(targetEntity="Template", mappedBy="location")
	  */
	private $templates;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $securityKey;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $securityCertificate;
	
	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $taxPayer;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $submited;
	
	function __construct()
	{
		$this->submited = false;
	}
	
	public function getSubmited()
	{
		return $this->submited;
	}

	public function setSubmited($submited)
	{
		$this->submited = $submited;
	}

	public function getTaxPayer()
	{
		return $this->taxPayer;
	}

	public function setTaxPayer($taxPayer)
	{
		$this->taxPayer = $taxPayer;
	}
	
	public function getSecurityKey()
	{
		return $this->securityKey;
	}

	public function setSecurityKey($securityKey)
	{
		$this->securityKey = $securityKey;
	}

	public function getSecurityCertificate()
	{
		return $this->securityCertificate;
	}

	public function setSecurityCertificate($securityCertificate)
	{
		$this->securityCertificate = $securityCertificate;
	}
	
	public function getTemplatesSorted()
	{
		$templates = $this->getTemplates()->toArray();
		
		$activeTemplates = array();
		
		foreach($templates as $template)
		{
			/* @var $template Template */
			if($template->getPriority() > 0)
			{
				$activeTemplates[] = $template;
			}
		}
		
		usort($activeTemplates, function($a, $b) { 
			
			/* @var $a Template */
			/* @var $b Template */
			
			if($a->getPriority() == $b->getPriority())
			{
				return 0;
			}
			
			return ($a->getPriority() > $b->getPriority())? -1 : 1;
		});
		
		return $activeTemplates;
	}

	public function getTemplates()
	{
		return $this->templates;
	}

	public function setTemplates($templates)
	{
		$this->templates = $templates;
	}

	public function getWorkingHours()
	{
		return $this->workingHours;
	}

	public function setWorkingHours($workingHours)
	{
		$this->workingHours = $workingHours;
	}
	
	public function getCompanyTaxNumber()
	{
		return $this->companyTaxNumber;
	}

	public function setCompanyTaxNumber($companyTaxNumber)
	{
		$this->companyTaxNumber = $companyTaxNumber;
	}

	public function getPostalCode()
	{
		return $this->postalCode;
	}

	public function setPostalCode($postalCode)
	{
		$this->postalCode = $postalCode;
	}

	public function getHouseNumber()
	{
		return $this->houseNumber;
	}

	public function setHouseNumber($houseNumber)
	{
		$this->houseNumber = $houseNumber;
	}

	public function getHouseNumberExtension()
	{
		return $this->houseNumberExtension;
	}

	public function setHouseNumberExtension($houseNumberExtension)
	{
		$this->houseNumberExtension = $houseNumberExtension;
	}

	public function getSettlement()
	{
		return $this->settlement;
	}

	public function setSettlement($settlement)
	{
		$this->settlement = $settlement;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function setCity($city)
	{
		$this->city = $city;
	}

	public function getStreet()
	{
		return $this->street;
	}

	public function setStreet($street)
	{
		$this->street = $street;
	}

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
	
	public function getDescription()
	{
		$data = array();
		
		if($this->getStreet()) $data[] = $this->getStreet() . ' ' . $this->getHouseNumber() . $this->getHouseNumberExtension();
		if($this->getCity()) $data[] = $this->getPostalCode () . ' ' . $this->getCity();
		
		return implode(', ', $data);
	}
}
