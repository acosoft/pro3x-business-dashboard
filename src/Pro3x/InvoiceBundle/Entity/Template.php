<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Template
 *
 * @ORM\Table(name="pro3x_templates")
 * @ORM\Entity(repositoryClass="Pro3x\InvoiceBundle\Entity\TemplateRepository")
 */
class Template
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
    private $dueDays;   
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $background;   

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $filename;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     */
    private $paymentMethod;

    /**
     * @ManyToOne(targetEntity="Location", inversedBy="templates")
      */
    private $location;

    /**
     * @ORM\Column(type="string")
     */
    private $transactionType;

    /**
     * @OneToMany(targetEntity="Invoice", mappedBy="template")
      */
    private $invoices;

    /**
     * @OneToMany(targetEntity="Invoice", mappedBy="tenderTemplate")
      */
    private $tenders;

    /**
     * @ORM\Column(type="boolean")
     */
    private $useGoogleCloud;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    function __construct()
    {
            $this->setPriority(1);
    }

    public function getTenders()
    {
            return $this->tenders;
    }

    public function setTenders($tenders)
    {
            $this->tenders = $tenders;
    }
    
    public function getBackground() {
        return $this->background;
    }

    public function setBackground($background) {
        $this->background = $background;
        return $this;
    }

    public function getPriority()
    {
            return $this->priority;
    }

    public function setPriority($priority)
    {
            $this->priority = $priority;
    }

    public function getUseGoogleCloud()
    {
            return $this->useGoogleCloud;
    }

    public function setUseGoogleCloud($useGoogleCloud)
    {
            $this->useGoogleCloud = $useGoogleCloud;
    }

    public function getUseGoogleCloudFormated()
    {
            if($this->useGoogleCloud == true)
                    return "Google Cloud Ispis";
            else
                    return "Direktni Ispis";
    }

    /**
     * 
     * @return Invoice
     */
    public function getInvoices()
    {
            return $this->invoices;
    }

    public function setInvoices($invoices)
    {
            $this->invoices = $invoices;
    }
        
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
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
	
    public function getName()
    {
            return $this->name;
    }

    public function setName($name)
    {
            $this->name = $name;
    }

    public function getFilename()
    {
            return $this->filename;
    }

    public function setFilename($filename)
    {
            $this->filename = $filename;
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

    public function getTransactionType()
    {
            return $this->transactionType;
    }

    public function setTransactionType($transactionType)
    {
            $this->transactionType = $transactionType;
    }

    public function getLocationName()
    {
            return $this->getLocation()->getName();
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
        return $this;
    }

    public function getDueDays() {
        return $this->dueDays;
    }

    public function setDueDays($dueDays) {
        $this->dueDays = $dueDays;
        return $this;
    }
}
