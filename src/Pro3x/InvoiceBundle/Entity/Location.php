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
class Location {

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
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $companyName;
    
    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $iban;
    
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $address;
    
    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $other;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $display;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $code;
    
    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $note1;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $note2;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $note3;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $logo;
    
    public function getLogo() {
        return $this->logo;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
        return $this;
    }
    
    public function getDisplay() {
        return $this->display;
    }

    public function getCode() {
        return $this->code;
    }

    public function setDisplay($display) {
        $this->display = $display;
        return $this;
    }

    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getOther() {
        return $this->other;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    public function setOther($other) {
        $this->other = $other;
        return $this;
    }
    
    public function getIban() {
        return $this->iban;
    }

    public function setIban($iban) {
        $this->iban = $iban;
        return $this;
    }
    
    public function getCompanyName() {
        return $this->companyName;
    }

    public function setCompanyName($companyName) {
        $this->companyName = $companyName;
        return $this;
    }

    function __construct() {
        $this->submited = false;
    }

    public function getSubmited() {
        return $this->submited;
    }

    public function setSubmited($submited) {
        $this->submited = $submited;
    }

    public function getTaxPayer() {
        return $this->taxPayer;
    }

    public function setTaxPayer($taxPayer) {
        $this->taxPayer = $taxPayer;
    }

    public function getSecurityKey() {
        return $this->securityKey;
    }

    public function setSecurityKey($securityKey) {
        $this->securityKey = $securityKey;
    }

    public function getSecurityCertificate() {
        return $this->securityCertificate;
    }

    public function setSecurityCertificate($securityCertificate) {
        $this->securityCertificate = $securityCertificate;
    }

    public function getTemplatesSorted() {
        $templates = $this->getTemplates()->toArray();

        $activeTemplates = array();

        foreach ($templates as $template) {
            /* @var $template Template */
            if ($template->getPriority() > 0) {
                $activeTemplates[] = $template;
            }
        }

        usort($activeTemplates, function($a, $b) {

            /* @var $a Template */
            /* @var $b Template */

            if ($a->getPriority() == $b->getPriority()) {
                return 0;
            }

            return ($a->getPriority() > $b->getPriority()) ? -1 : 1;
        });

        return $activeTemplates;
    }

    public function getTemplates() {
        return $this->templates;
    }

    public function setTemplates($templates) {
        $this->templates = $templates;
    }

    public function getWorkingHours() {
        return $this->workingHours;
    }

    public function setWorkingHours($workingHours) {
        $this->workingHours = $workingHours;
    }

    public function getCompanyTaxNumber() {
        return $this->companyTaxNumber;
    }

    public function setCompanyTaxNumber($companyTaxNumber) {
        $this->companyTaxNumber = $companyTaxNumber;
    }

    public function getPostalCode() {
        return $this->postalCode;
    }

    public function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }

    public function getHouseNumber() {
        return $this->houseNumber;
    }

    public function setHouseNumber($houseNumber) {
        $this->houseNumber = $houseNumber;
    }

    public function getHouseNumberExtension() {
        return $this->houseNumberExtension;
    }

    public function setHouseNumberExtension($houseNumberExtension) {
        $this->houseNumberExtension = $houseNumberExtension;
    }

    public function getSettlement() {
        return $this->settlement;
    }

    public function setSettlement($settlement) {
        $this->settlement = $settlement;
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function getStreet() {
        return $this->street;
    }

    public function setStreet($street) {
        $this->street = $street;
    }

    public function getPositions() {
        return $this->positions;
    }

    public function setPositions($positions) {
        $this->positions = $positions;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getDescription() {
        $data = array();

        if ($this->getStreet())
            $data[] = $this->getStreet() . ' ' . $this->getHouseNumber() . $this->getHouseNumberExtension();
        if ($this->getCity())
            $data[] = $this->getPostalCode() . ' ' . $this->getCity();

        return implode(', ', $data);
    }
    
    public function getNote1() {
        return $this->note1;
    }

    public function getNote2() {
        return $this->note2;
    }

    public function getNote3() {
        return $this->note3;
    }

    public function setNote1($note1) {
        $this->note1 = $note1;
        return $this;
    }

    public function setNote2($note2) {
        $this->note2 = $note2;
        return $this;
    }

    public function setNote3($note3) {
        $this->note3 = $note3;
        return $this;
    }

}
