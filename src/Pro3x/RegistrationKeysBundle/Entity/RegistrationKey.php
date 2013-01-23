<?php

namespace Pro3x\RegistrationKeysBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * RegistrationKey
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class RegistrationKey
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

	/*
        params.add("PCODE"); //sifra proizvoda
        params.add("REFNO"); 
        params.add("FIRSTNAME");
        params.add("LASTNAME");
        params.add("COMPANY");
        params.add("EMAIL");
        params.add("COUNTRY");
        params.add("CITY");
        params.add("ZIPCODE");
        
        if(includeDateParams == true)
        {
            params.add("validfrom");
            params.add("validto");            
        }
	 * 
	 * 
		Document doc = DocumentBuilderFactory.newInstance().newDocumentBuilder().parse(new File(path));
		List<String> params = getLicenceParams(true);

		String data = "//";

		for(String param : params)
		{
			String info = doc.getElementsByTagName(param.toLowerCase()).item(0).getTextContent();
			data += " " + info.trim() + " //";
		}

		MessageDigest sha = MessageDigest.getInstance("SHA1");
		String base64hash = new BASE64Encoder().encode(sha.digest(data.getBytes("UTF-8")));

		MessageDigest md5 = MessageDigest.getInstance("MD5");
		byte[] keyCode = md5.digest(application.getBytes("UTF-8"));

		Cipher rsa = Cipher.getInstance("AES/ECB/PKCS5Padding");
		rsa.init(Cipher.DECRYPT_MODE, new SecretKeySpec(keyCode, "AES"));

		String base64sign = doc.getElementsByTagName("signature").item(0).getTextContent();
		byte[] hash64code = rsa.doFinal(new BASE64Decoder().decodeBuffer(base64sign));

		String hashString = new BASE64Encoder().encode(hash64code);
		return base64hash.equals(hashString);
	 */

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
	/**
	 * @ManyToOne(targetEntity="\Pro3x\InvoiceBundle\Entity\Product", inversedBy="registrationKeys")
	  */
	private $product;
	
	/**
	 * @ManyToOne(targetEntity="\Pro3x\InvoiceBundle\Entity\Customer", inversedBy="registrationKeys")
	  */
	private $customer;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $validFrom;
	
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $validTo;
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\Product
	 */
	public function getProduct()
	{
		return $this->product;
	}

	public function setProduct($product)
	{
		$this->product = $product;
		return $this;
	}
	
	public function getProductDescription()
	{
		return $this->getProduct()->getName();
	}

	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\Customer
	 */
	public function getCustomer()
	{
		return $this->customer;
	}
	
	public function getCustomerDescription()
	{
		return $this->getCustomer()->getDescriptionFormated();
	}

	public function setCustomer($customer)
	{
		$this->customer = $customer;
		return $this;
	}
	
	/**
	 * 
	 * @return \DateTime
	 */
	public function getValidFrom()
	{
		return $this->validFrom;
	}
	
	public function getValidFromFormated()
	{
		return $this->getValidFrom()->format("d.m.Y");
	}

	public function setValidFrom($validFrom)
	{
		$this->validFrom = $validFrom;
	}

	/**
	 * 
	 * @return \DateTime
	 */
	public function getValidTo()
	{
		return $this->validTo;
	}

	public function setValidTo($validTo)
	{
		$this->validTo = $validTo;
	}
	
	public function getValidToFormated()
	{
		return $this->getValidTo()->format("d.m.Y");
	}
}
