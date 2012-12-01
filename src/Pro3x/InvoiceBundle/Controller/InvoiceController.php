<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\InvoiceBundle\Entity\InvoiceItem;
use Symfony\Component\HttpFoundation\Response;
use Pro3x\InvoiceBundle\Entity\Product;
use Pro3x\InvoiceBundle\Parsers\CodeParser;
use Pro3x\InvoiceBundle\Parsers\AmountCodeParser;
use Pro3x\InvoiceBundle\Parsers\AmountCodePercentParser;
use Pro3x\InvoiceBundle\Entity\Invoice;
use Pro3x\Online\TableParams;
use Pro3x\InvoiceBundle\Form\CustomerType;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @Route("/admin/invoices")
 */
class InvoiceController extends AdminController
{
	/**
	 * @Route("/add", name="add_invoice")
	 * @Template("Pro3xInvoiceBundle:Invoice:invoice.html.twig")
	 */
	public function addAction()
	{
		$invoice = new Invoice();
		$invoice->setStatus('skica');
		
		$invoice->setUser($this->getUser());
		$position = $this->getPositionRepository()->find($this->getUser()->getPosition());
		$invoice->setPosition($position);
		
		
		$manager = $this->getDoctrine()->getEntityManager();
		$manager->persist($invoice);
		$manager->flush();
		
		return $this->redirect($this->generateUrl('edit_invoice', array('id' => $invoice->getId(), 'back' => $this->getParam('back'))));
	}
	
	/**
	 * @Route("/change-customer/{id}/{page}/{customerId}", defaults={"customerId" = null, "page" = 1}, name="change_invoice_customer")
	 * @Template("::table.html.twig")
	 */
	public function invoiceCustomerAction($id, $page, $customerId)
	{
		$invoice = $this->getInvoiceRepository()->findOneById($id); /* @var $invoice Invoice */
		$this->redirect404($invoice);
				
		$queryBuilder = $this->getCustomerRepository()->createQueryBuilder('c')
				->setMaxResults($this->getPageSize())
				->setFirstResult($this->getPageOffset($page));
		
		if($this->getRequest()->isMethod('post'))
		{
			$query = $this->getParam('query');
			
			if(strlen($query) < 3) $this->setWarningMessage('Potrebno je minimalno tri znaka za uspješno pretraživanje');
			$queryBuilder->andWhere('c.name LIKE :name OR c.taxNumber LIKE :name')
					->setParameters(array(':name' => '%' . $query . '%'));

			$items = $queryBuilder->getQuery()->execute();
		}
		else if($customerId != null)
		{
			if($customerId == 'empty')
			{
				$invoice->setCustomer(null);
			}
			else
			{
				$customer = $this->getCustomerRepository()->findOneById($customerId);
				$this->redirect404($customer);

				$invoice->setCustomer($customer);
			}
			
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($invoice);
			$manager->flush();
			
			return $this->redirect($this->getParam('back'));
		}
		else
		{
			$items = $queryBuilder->getQuery()->execute();
		}
		
		$pager = new Paginator($queryBuilder->getQuery());
		$count = $pager->count();
		
		$params = new TableParams();
		
		return $params->setTitle('Izbor kupca')
				->setIcon('client_search')

				->addColumn('name', "Naziv")
				->addColumn('taxNumber', 'OIB')
				->addColumn('address')
				->addColumn('location')
				
				->setAddRoute('add_client')
				
				->setPagerVisible(true)
				->setPageCount($this->getPageCount($count))
				->setPage($page)
				->addPagerParam('id', $id)
				->addPagerParam('customerId', $customerId)
				
				->setSearchVisible(true)
				->setSelectParam('customerId')
				->setPlaceholder('Naziv ili OIB kupca')
				
				->addPagerParam('query', $this->getParam('query'))
				->setItems($items)->getParams();
	}
	
	/**
	 * @Route("/add-item", name="add_invoice_item")
	 * @Template()
	 */
	public function addItemAction()
	{
		$invoiceId = $this->getParam('invoice');
		$invoice = $this->getInvoiceRepository()->findOneById($invoiceId); /* @var $invoice Invoice */
		$this->redirect404($invoice);
			
		$data = $this->getRequest()->get('code');
		
		foreach(array(new AmountCodePercentParser(), new AmountCodeParser(), new CodeParser()) as $parser) /* @var $parser \Pro3x\InvoiceBundle\Parsers\BaseParser */
		{
			if($parser->parse($data))
			{
				break;
			}
		}
		
		$product = $this->getProductRepository()->findOneByBarcode($parser->getCode()); /* @var $product Product */

		$item = new InvoiceItem($product);
		$item->setNumeric($this->getNumeric());
		$item->setAmount($parser->getAmount());
		$item->setDiscount($parser->getDiscount());
		$item->setInvoice($invoice);
		
		$manager = $this->getDoctrine()->getEntityManager();
		$manager->persist($item);
		$manager->flush();
		
		$itemView = $this->renderView('Pro3xInvoiceBundle:Invoice:addItem.html.twig', array('item' => $item));
		
		return new Response(json_encode(array(
			'total' => $this->formatNumber($invoice->getTotal(), 2),
			'item' => $itemView), JSON_FORCE_OBJECT));
	}
	
	/**
	 * @Route("/save-invoice", name="save_invoice")
	 * @Template()
	 */
	public function saveInvoiceAction()
	{
		
		
		return array();
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_invoice")
	 * @Template("Pro3xInvoiceBundle:Invoice:invoice.html.twig")
	 */
	public function editAction($id)
	{
		$invoice = $this->getInvoiceRepository()->findOneById($id); /* @var $invoice Invoice */
		$this->redirect404($invoice);
				
		$invoice->setNumeric($this->getNumeric());
		return array('total' => $this->formatNumber($invoice->getTotal(), 2), 'invoice' => $invoice);
	}
	
	/**
	 * @Route("/print/{id}", name="print_invoice")
	 * @Template()
	 */
	public function printAction($id)
	{	
		$repository = $this->getInvoiceRepository();
		$invoice = $repository->findOneById($id); /* @var $invoice Invoice */
		$this->redirect404($invoice);
		
		$manager = $this->getDoctrine()->getEntityManager();
		
		if($invoice->getSequence() == null)
		{
			$this->getDoctrine()->getEntityManager()->transactional(function($manager) use ($invoice) {
				$position = $invoice->getPosition(); /* @var $position Pro3x\InvoiceBundle\Entity\Position */

				$manager->refresh($position);

				$invoice->setSequence($position->getSequence());
				$position->setSequence($position->getSequence() + 1);

				$manager->persist($position);
				$manager->persist($invoice);
				$manager->flush();
			});
		}
		
		$invoice->setNumeric($this->getNumeric());
		$invoice->setStatus($this->getParam('mode', 'cash'));
		
		foreach($invoice->getItems() as $item) /* @var $item InvoiceItem */
		{
			$item->setNumeric($invoice->getNumeric());
		}
		
		$print = $this->renderView('Pro3xInvoiceBundle:Invoice:print-' . $this->getParam('mode', 'cash') . '.html.twig', array('hello' => 'Hello Google Cloud Print : )', 'invoice' => $invoice));
		
		$direct = $this->getParam('print', 'true');
		
		if($direct === 'true')
		{
			$response = new Response(base64_encode($print));
		}
		else
		{
			$response = new Response($print);
		}
		
		return $response;
	}
	
	/**
	 * @Route("/delete/{id}", name="delete_invoice")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getInvoiceRepository(), 'Račun je izbrisan');
	}
	
	/**
	 * @Route("/cancel/{id}", name="cancel_invoice")
	 */
	public function cancelAction($id)
	{
		$invoice = $this->getInvoiceRepository()->find($id);
		
		if($invoice->getItems()->count() == 0)
		{
			$manager = $this->getDoctrine()->getEntityManager();
			$manager->remove($invoice);
			$manager->flush();
		}
		
		return $this->redirect($this->getParam('back'));
	}
	
	/**
	 * @Route("/delete-invoice-item/{id}", name="delete_invoice_item")
	 */
	public function deleteItemAction($id)
	{
		$item = $this->getInvoiceItemRepository()->findOneById($id); /* @var $item InvoiceItem */
		$invoice = $item->getInvoice();
		
		$manager = $this->getDoctrine()->getManager();
		$manager->remove($item);
		$manager->flush();
		
		return new Response(json_encode(array('result' => true, 'total' => $this->formatNumber($invoice->getTotal(), 2)), JSON_FORCE_OBJECT));
	}
	
	/**
     * @Route("/{page}", name="invoices", defaults={"page" = 1})
     * @Template("::table.html.twig")
     */
    public function indexAction($page)
    {
		$params = new TableParams();
		
		$page = $this->getRequest()->get('page', 1);
		
		$repository = $this->getInvoiceRepository();
		$count = $repository->count();
		$items = $repository->findBy(array(), array('id' => 'DESC'), $this->getPageSize(), $this->getPageOffset($page));
		
		return $params->setTitle('Popis računa')
				->setIcon('invoice')
				
				->addColumn('sequenceFormated', 'ID')
				->addColumn('dateTimeFormated', 'Datum', 125, 'center')
				->addColumn('customer.name', 'Kupac')
				->addColumnTrans('status', "Status")
				
				->setAddRoute('add_invoice')
				->setEditRoute('edit_invoice')
				->setDeleteRoute('delete_invoice')
				->setDeleteColumn('id')
				->setDeleteType('račun')
				
				->setPagerVisible(true)
				->setPageCount($this->getPageCount($count))
				->setPage($page)
				->setItems($items)->getParams();
    }
}
