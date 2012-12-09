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
				->where('i.user = :user AND i.sequence IS NOT null AND i.dailyReport IS null')
				->setParameter('user', $this->getUser())
				->groupBy('t.transactionType')
				->getQuery()
				->getResult();
//				->where('u.id = :user_id')
//				->setParameter('user_id', $userId);
		
		$total = 0;
		
		foreach ($items as &$item)
		{
			$item['transactionType'] = TemplateType::formatTransactionType($item['transactionType']);
			$total += $item['total'];
		}
		
		$total = $this->getNumeric()->getNumberFormatter()->format($total);
		
		return array('items' => $items, 'total' => $total);
	}
	
	/**
	 * @Route("/lock-position/{id}", name="lock_position")
	 */
	public function lockInvoicesAction($id)
	{
		if($this->getUser()->getId() == $id)
		{
			$manager = $this->getDoctrine()->getEntityManager();
			$repository = $this->getInvoiceRepository();
			$user = $this->getUserRepository()->find($id);

			$manager->transactional(function($manager) use ($user, $repository) {

				$dailyTotal = $repository->createQueryBuilder('i')
						->select('sum(i.invoiceTotal)')
						->where('i.dailyReport IS null AND i.user = :user AND i.sequence IS NOT null')
						->setParameter('user', $user)
						->getQuery()->getSingleScalarResult();

				$dailyReport = new \Pro3x\InvoiceBundle\Entity\DailySalesReport();
				$dailyReport->setOperator($user);
				$dailyReport->setTotal($dailyTotal);

				$manager->persist($dailyReport);
				//$manager->flush();

				$invoices = $repository->createQueryBuilder('i')
						->select()
						->where('i.dailyReport IS null AND i.user = :user AND i.sequence IS NOT null')
						->setParameter('user', $user)
						->getQuery()->execute();

				foreach($invoices as &$invoice) /* @var $invoice \Pro3x\InvoiceBundle\Entity\Invoice */
				{
					$invoice->setDailyReport($dailyReport);
					$manager->persist($invoice);
				}

				$manager->flush();
				
			});

			$this->setMessage('Blagajna je zaključena');
			return $this->redirect($this->generateUrl('dashboard'));
		}
		else
		{
			$this->setWarning('Nemate dozvolu da zaključite blagajnu drugog korisnika');
			return $this->redirect($this->generateUrl('daily_total', array('id' => $id)));
		}
	}
}

?>
