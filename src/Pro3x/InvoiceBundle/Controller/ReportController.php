<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Pro3x\Online\TableParams;
use Pro3x\InvoiceBundle\Form\LocationType;
use Pro3x\Online\EditHandler;
use Pro3x\InvoiceBundle\Form\TemplateType;


/**
 * @Route("/admin/reports")
 */
class ReportController extends AdminController
{
	/**
	 * @Route("/", name="daily_total")
	 * @Template()
	 */
	public function dailyTotalAction()
	{
		$userId = $this->getUser()->getId();
		$items = $this->getInvoiceRepository()->createQueryBuilder('i')
				->select('sum(s.unitPrice * s.amount ) price')
				->join('i.user', 'u')
				->join('i.items', 's')
				->where('i.user = :user')
				->setParameter('user', $this->getUser())
				->getQuery()
				->getResult();
//				->where('u.id = :user_id')
//				->setParameter('user_id', $userId);
		
				
		$params = new TableParams();
		
		$editUserUrl = $this->generateUrl('edit_user', array('id' => $this->getUser()->getId(), 'back' => $this->getRequest()->getUri()));

		$params->setTitle('Pregled Dnevnog Prometa')
				->setIcon('template')
				->addColumn('price', 'Naziv lokacije')
				->setDeleteType('predložak')
				->setDeleteColumn('name')
				->setRoutes('template')
				->setItems($items)
				->setPagerVisible(false);
				
		
		return $params->getParams();
	}
}

?>
