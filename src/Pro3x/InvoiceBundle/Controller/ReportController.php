<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

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
	 * @Route("/lock-position/{id}/{position}", name="lock_position")
	 */
	public function lockInvoicesAction($id, $position)
	{
		if($this->getUser()->getId() == $id || $this->isInRole('close_daily_reports'))
		{
			$manager = $this->getDoctrine()->getEntityManager();
			$repository = $this->getInvoiceRepository();
			
			$user = $this->getUserRepository()->find($id);
			$position = $this->getPositionRepository()->find($position);
			
			$controller = $this;
			
			$manager->transactional(function($manager) use ($user, $controller, $position, $repository) {

				$dailyTotal = $repository->createQueryBuilder('i')
						->select('sum(i.invoiceTotal)')
						->where('i.dailyReport IS null AND i.user = :user AND i.position = :position AND i.sequence IS NOT null')
						->setParameter('position', $position)
						->setParameter('user', $user)
						->getQuery()->getSingleScalarResult();

				$invoices = $repository->createQueryBuilder('i')
						->select()
						->where('i.dailyReport IS null AND i.user = :user AND i.sequence IS NOT null')
						->setParameter('user', $user)
						->getQuery()->execute();
				
				if(count($invoices) > 0)
				{
					$dailyReport = new \Pro3x\InvoiceBundle\Entity\DailySalesReport();
					$dailyReport->setOperator($user);
					$dailyReport->setPosition($position);
					$dailyReport->setTotal($dailyTotal);

					$manager->persist($dailyReport);

					foreach($invoices as &$invoice) /* @var $invoice \Pro3x\InvoiceBundle\Entity\Invoice */
					{
						$invoice->setDailyReport($dailyReport);
						$manager->persist($invoice);
					}

					$manager->flush();
					
					$controller->setMessage('Blagajna je zaključena');
					
				}
				else
				{
					$controller->setWarning('Zaključak blagajne nije napravljen, jer nema novih računa');
				}
				
			});
			
			return $this->redirect($this->generateUrl('daily_total', array('id' => $id, 'position' => $position->getId())));
		}
		else
		{
			$this->setWarning('Nemate dozvolu da zaključite blagajnu drugog korisnika');
			return $this->redirect($this->generateUrl('daily_total', array('id' => $id)));
		}
	}
	
	/**
	 * @Route("/print-daily-report/{id}/{report}", name="print_daily_total", defaults={"report"=null})
	 * @Template()
	 */
	public function printDailyReportAction($id, $report)
	{
		$report = $this->buildReport($id, $report);
		$user = $this->getUserRepository()->find($id);
		
		$params = array('items' => $report[0], 'total' => $report[1], 'created' => $report[2], 'operator' => $user);
		
		if($this->getParam('print', true) == 'true')
		{
			return $params;
		}
		else
		{
			return new Response(base64_encode($this->renderView('Pro3xInvoiceBundle:Report:printDailyReport.html.twig', $params)));
		}
	}
	
	public function buildReport($id, $report)
	{
		$query = $this->getInvoiceRepository()->createQueryBuilder('i')
				->select('t.transactionType, sum(i.invoiceTotal ) total')
				->join('i.template', 't');
		
		if($report == null)
		{
			$query->where('i.user = :user AND i.sequence IS NOT null AND i.dailyReport IS null');
			$selectedDate = new \DateTime('now');
		}
		else
		{
			$query->where('i.user = :user AND i.sequence IS NOT null AND i.dailyReport = :report')
					->setParameter('report', intval($report));
			
			$selectedReport = $this->getDailyReportRepository()->find($report);
			$selectedDate = $selectedReport->getCreated();
		}
		
		$items = $query->setParameter('user', intval($id))
				->groupBy('t.transactionType')
				->getQuery()
				->getResult();
		
		$total = 0;
		
		foreach ($items as &$item)
		{
			$item['transactionType'] = TemplateType::formatTransactionType($item['transactionType']);
			$total += $item['total'];
		}
		
		$formater = $this->getNumeric()->getNumberFormatter();
		$total = $formater->format($total);
		
		return array($items, $total, $selectedDate);
	}
	
	
	/**
	 * @Route("/{id}/{position}/{page}/{report}", name="daily_total", defaults={"report"=null, "page"=1})
	 * @Template()
	 */
	public function dailyTotalAction($id, $position, $report, $page)
	{
		//selected report
		$result = $this->buildReport($id, $report);
		
		$items = $result[0];
		$total = $result[1];
		$selectedDate = $result[2];
		
		//current report
		$result = $this->buildReport($id, null);
		$currentTotal = $result[1];
		
		$repository = $this->getDailyReportRepository();
		$reports = $repository->findBy(array('operator' => $id), array('created' => 'DESC'), $this->getPageSize(), $this->getPageOffset($page));
		
		$count = $this->getDailyReportRepository()->createQueryBuilder('r')
				->select('count(r)')
				->where('r.operator = :operator')
				->setParameter('operator', $id)
				->getQuery()->getSingleScalarResult();
				
		$pageCount = $this->getPageCount($count);
		
		$pagerParams = new \Pro3x\Online\PagerParams();
		$pagerParams->setPage($page)->setPageCount($pageCount)
				->addRouteParam('id', $id)
				->addRouteParam('report', $report)
				->addRouteParam('position', $position);
		
		$user = $this->getUserRepository()->find($id);
		
		return array_merge(array('items' => $items, 'operator' => $user, 'currentTotal' => $currentTotal, 'total' => $total, 'reports' => $reports, 'selectedDate' => $selectedDate), $pagerParams->getParams());
	}
}

?>
