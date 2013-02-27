<?php

namespace Pro3x\WarehouseBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Pro3x\InvoiceBundle\Form\CustomerType;
use Pro3x\Online\AddHandler;
use Pro3x\WarehouseBundle\Entity\Supplier;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/warehouse/suppliers")
 */
class SupplierController extends AdminController
{
	/**
	 * @Route("/add", name="add_supplier")
	 * @Template("::edit.html.twig")
	 */
	public function addSupplierAction()
	{
		$handler = new AddHandler($this, new Supplier());
		
		$handler->setTitle("Novi dobavljač")
				->setIcon("client_add")
				->setSuccessMessage("Dobavljač je uspjšeno spremljen")
				->setFormType(new CustomerType());

		return $handler->execute();
	}
	/**
	 * @Route("/", name="index_suppliers")
	 * @Template()
	 */
	public function indexAction()
	{
		

		return array();
	}
}

?>
