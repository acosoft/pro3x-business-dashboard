<?php

namespace Pro3x\WarehouseBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/warehouse")
 */
class ReceiptController extends AdminController
{
	/**
	 * @Route("/add", name="receipts_add")
	 * @Template()
	 */
	public function addReceiptAction()
	{


		return array();
	}
	
	
	/**
	 * @Route("/", name="receipts_index")
	 * @Template()
	 */
	public function indexAction()
	{
		
	}
}

?>
