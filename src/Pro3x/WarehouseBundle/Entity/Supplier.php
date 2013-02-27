<?php

namespace Pro3x\WarehouseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Pro3x\InvoiceBundle\Entity\Customer;

/**
 * Supplier
 *
 * @ORM\Entity(repositoryClass="Pro3x\WarehouseBundle\Entity\SupplierRepository")
 */
class Supplier extends Customer
{
    
}
