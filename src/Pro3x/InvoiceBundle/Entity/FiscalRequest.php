<?php

namespace Pro3x\InvoiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FiscalRequest
 *
 * @ORM\Table(name="pro3x_fiscal_requests")
 * @ORM\Entity
 */
class FiscalRequest
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
     * @var string
     *
     * @ORM\Column(name="request", type="text")
     */
    private $request;
    
    /**
     * @var string
     *
     * @ORM\Column(name="response", type="text")
     */
    private $response;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    function getRequest() {
        return $this->request;
    }

    function getResponse() {
        return $this->response;
    }

    function setRequest($request) {
        $this->request = $request;
        return $this;
    }

    function setResponse($response) {
        $this->response = $response;
        return $this;
    }


}
