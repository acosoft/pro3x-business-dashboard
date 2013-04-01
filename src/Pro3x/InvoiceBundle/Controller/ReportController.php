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
	 * 
	 * @return \Pro3x\InvoiceBundle\ReportingServices
	 */
	public function getReportingServices()
	{
		return $this->get('invoice.reporting.services');
	}
	
	/**
	 * @Route("/print-customer-history/{customer}", name="customer_history")
	 * @Template("Pro3xInvoiceBundle:Report:printCustomerHistoryReport.html.twig")
	 */
	public function customerHistoryAction($customer)
	{
		$sql = "SELECT 
					weekofyear(p1_.created) week,
					date(p1_.created) purchased,
					p0_.description AS description, 
					p0_.taxedPrice AS unitPrice, 
					sum(
					  p0_.amount 
					) AS totalAmount, 
					sum(
					  p0_.totalTaxedPrice 
					) AS totalPrice, 
					sum(
					  p0_.discountAmount 
					) AS discount, 
					sum(
					  p0_.dicountPrice 
					) AS total 
				  FROM 
					pro3x_invoices p1_ 
				  INNER JOIN 
					pro3x_clients p2_ ON p1_.customer_id = p2_.id 
				  INNER JOIN 
					pro3x_invoice_items p0_ ON p1_.id = p0_.invoice_id 
				  WHERE 
					p1_.customer_id = :customer
				  GROUP BY 
					date(p1_.created),
					p0_.description, 
					p0_.unitPrice
				  ORDER BY date(p1_.created) desc";
		
		
		
		$database = $this->get('database_connection'); /* @var $service \Doctrine\DBAL\Connection */
		$query = $database->prepare($sql); /* @var $query \Doctrine\DBAL\Statement */
		$query->bindParam('customer', $customer, \PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll();
		
//		$data = $this->getInvoiceRepository()->createQueryBuilder('i')
//				->join('i.customer', 'c')->join('i.items', 'ii')
//				->select('ii.description AS description, ii.taxedPrice AS unitPrice, sum(ii.amount) AS totalAmount, '
//						. 'sum(ii.totalTaxedPrice) AS totalPrice, sum(ii.discountAmount) AS discount, sum(ii.dicountPrice) AS total')
//				->where('i.customer = :customer')->setParameter('customer', $customer)
//				->groupBy('ii.description, ii.unitPrice')
//				->getQuery()
//				->getResult();

		$customerInfo = $this->getCustomerRepository()->find($customer);
		return array('customer' => $customerInfo, 'data' => $data, 'operator' => $this->getUser(), 'created' => date('now'));
	}
	
	private function getTotalSalesRangeForm()
	{
		return $this->createFormBuilder()
				->add('location', 'entity', array(
					'label' => 'Naziv Lokacije', 
					'expanded' => false, 'property' => 'name', 
					'multiple' => false, 
					'class' => 'Pro3xInvoiceBundle:Location'))
				->add('start_date', 'date', array('empty_value' => 'blank', 'widget' => 'single_text', 'format' => 'dd.MM.yyyy', 'label' => 'Početni datum', 'required' => true, 'attr' => array('class' => 'pro3x_date')))
				->add('end_date', 'date', array('empty_value' => 'blank', 'widget' => 'single_text', 'format' => 'dd.MM.yyyy', 'label' => 'Završni datum', 'required' => true, 'attr' => array('class' => 'pro3x_date')))
				->getForm();
	}
	
	/**
	 * @Route("/total-sales", name="select_total_sales_range")
	 * @Template()
	 */
	public function selectTotalSalesRangeAction()
	{
		$form = $this->getTotalSalesRangeForm();
		return array('form' => $form->createView());
	}
	
	/**
	 * @Route("/print-total-sales", name="print_total_sales_report")
	 * @Template()
	 */
	public function printTotalSalesReportAction(\Symfony\Component\HttpFoundation\Request $request)
	{
		$operater = $this->getUser();
		
		$form = $this->getTotalSalesRangeForm();
		$form->bind($request);
		
		$range = $form->getData();
		
		$start = $range['start_date'];
		$end = $range['end_date'];
		$location = $range['location'];
		
		$data = $this->getProductReportQuery()
				->join('i.position', 'p')
				->where('p.location = :location AND i.created BETWEEN :start AND :end AND i.sequence is not null')
				->setParameter('location', $location)
				->setParameter('start', $start)
				->setParameter('end', $end)->getQuery()->getResult();
		
		return array('location' => $location, 'operator' => $operater, 
			'start' => $start, 'end' => $end, 'data' => $data, 'created' => new \DateTime('now'));
	}
	
	/**
	 * @Route("/csv/total-sales-report", name="download_csv_total_sales_report")
	 * @Template()
	 */
	public function downloadTotalSalesReportAction(\Symfony\Component\HttpFoundation\Request $request)
	{
		$operater = $this->getUser();

		$start = \DateTime::createFromFormat('d.m.Y', $this->getParam('start_date'));
		$end = \DateTime::createFromFormat('d.m.Y', $this->getParam('end_date'));
		$location = $this->getParam('location');
		
		$data = $this->getProductReportQuery()
				->join('i.position', 'p')
				->where('p.location = :location AND i.created BETWEEN :start AND :end AND i.sequence is not null')
				->setParameter('location', $location)
				->setParameter('start', $start)
				->setParameter('end', $end)->getQuery()->getResult();
		
		$xf = fopen('php://memory', 'rw');
		
		//->select('ii.description AS description, ii.unit, ii.taxedPrice AS unitPrice, sum(ii.amount) AS totalAmount, '
		//'sum(ii.totalTaxedPrice) AS totalPrice, sum(ii.discountAmount) AS discount, sum(ii.dicountPrice) AS total')
		
		fputcsv($xf, array('Naziv', 'Jedinica', 'Jedinična cijena', 'Količina', 'Osnovica', 'Popust', 'Ukupno'));
		
		foreach($data as $row)
		{
			fputcsv($xf, array($row['description'], $row['unitPrice'], $row['unit'], $row['totalAmount'], $row['totalPrice'], $row['discount'], $row['total']));
		}
		
		rewind($xf);
		$csv = stream_get_contents($xf);
		
		return new Response($csv,  200, array('Content-type' => "text/csv",
					'Content-Disposition' => 'attachment; filename=report.csv'));
	}
	
	/**
	 * @Route("/print-customers", name="print_customers")
	 * @Template()
	 */
	public function printCustomersAction()
	{
		$search = $this->getParam('search');
		$result = $this->getCustomerRepository()->findBySearchQuery($search);
		return array('data' => $result['items'], 'operator' => $this->getUser());
	}
	
	/**
	 * @Route("/lock-position/{id}", name="lock_position")
	 */
	public function lockInvoicesAction($id)
	{
		if($this->getUser()->getId() == $id || $this->isInRole('close_daily_reports'))
		{
			$manager = $this->getDoctrine()->getEntityManager();
			$repository = $this->getInvoiceRepository();
			
			$user = $this->getUserRepository()->find($id); /* @var $user \Pro3x\SecurityBundle\Entity\User */
			$position = $this->getPositionRepository()->find($user->getPosition());
			
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
	 * @Route("/print-daily-totals/{id}/{report}", name="print_daily_total", defaults={"report"=null})
	 * @Template()
	 */
	public function printDailyReportAction($id, $report)
	{
		$report = $this->buildReport($id, $report);
		$user = $this->getUserRepository()->find($id);
		
		$dailyReportTemplate = $this->getReportingServices()->getDailyReportTemplate();
		$params = array('items' => $report[0], 'total' => $report[1], 'created' => $report[2], 'operator' => $user);
		
		if($this->getParam('print', true) == 'true')
		{
			return $this->render($dailyReportTemplate, $params);
		}
		else
		{
			return new Response(base64_encode($this->renderView($dailyReportTemplate, $params)));
		}
	}
	
	/**
	 * @Route("/printy-daily-product-report/{user}/{report}", name="print_daily_product_report", defaults={"report"=null})
	 * @Template()
	 */
	public function printDailyProductReportAction($user, $report)
	{
		$params = $this->buildProductReport($user, $report);
		$params['operator'] = $this->getUserRepository()->find($user);
		
		return $params;
	}
	
	private function getProductReportQuery()
	{
		return $this->getInvoiceRepository()->createQueryBuilder('i')
				->join('i.items', 'ii')
				->select('ii.description AS description, ii.unit, ii.taxedPrice AS unitPrice, sum(ii.amount) AS totalAmount, '
						. 'sum(ii.totalTaxedPrice) AS totalPrice, sum(ii.discountAmount) AS discount, sum(ii.dicountPrice) AS total')
				->groupBy('ii.description, ii.unit, ii.unitPrice')
				->orderBy('ii.description', 'ASC');
	}
	
	private function buildProductReport($user, $report)
	{
		$query = $this->getProductReportQuery();
		$selectedDate = $this->appendWhere($query, $user, $report);
		
		return array('data' => $query->getQuery()->getResult(), 'created' => $selectedDate);
	}
	
	private function appendWhere($query, $user, $report)
	{
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
		
		$query->setParameter('user', intval($user));
		
		return $selectedDate;
	}
	
	public function buildReport($id, $report)
	{
		$query = $this->getInvoiceRepository()->createQueryBuilder('i')
				->select('t.transactionType, sum(i.invoiceTotal ) total')
				->join('i.template', 't');
		
		$selectedDate = $this->appendWhere($query, $id, $report);
		
		$items = $query->groupBy('t.transactionType')
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
	 * @Route("/daily-total/{id}/{page}/{report}", name="daily_total", defaults={"report"=null, "page"=1})
	 * @Template()
	 */
	public function dailyTotalAction($id, $report, $page)
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
				->addRouteParam('report', $report);
		
		$user = $this->getUserRepository()->find($id);
		
		return array_merge(array('items' => $items, 'operator' => $user, 'currentTotal' => $currentTotal, 'total' => $total, 'reports' => $reports, 'selectedDate' => $selectedDate), $pagerParams->getParams());
	}
}

?>
