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
				->select('t.transactionType, sum(i.invoiceTotal ) total')
				->join('i.template', 't')
				->where('i.user = :user AND i.sequence IS NOT null')
				->setParameter('user', $this->getUser())
				->groupBy('t.transactionType')
				->getQuery()
				->getResult();
//				->where('u.id = :user_id')
//				->setParameter('user_id', $userId);
		
		foreach ($items as &$item)
		{
			$item['transactionType'] = TemplateType::formatTransactionType($item['transactionType']);
		}
				
		$params = new TableParams();
		
		$editUserUrl = $this->generateUrl('edit_user', array('id' => $this->getUser()->getId(), 'back' => $this->getRequest()->getUri()));

		return array('items' => $items);
	}
}

?>
