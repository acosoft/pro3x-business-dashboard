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
		$invoice->setStatus('draft');
		
		$manager = $this->getDoctrine()->getEntityManager();
		$manager->persist($invoice);
		$manager->flush();
		
		return $this->redirect($this->generateUrl('edit_invoice', array('id' => $invoice->getId(), 'back' => $this->getParam('back'))));
	}
	
	/**
	 * @Route("/change-customer/{id}/{customerId}", defaults={"customerId" = null}, name="change_invoice_customer")
	 * @Template()
	 */
	public function invoiceCustomerAction($id, $customerId)
	{
		$invoice = $this->getInvoiceRepository()->findOneById($id); /* @var $invoice Invoice */
		$this->redirect404($invoice);
		
		$form = $this->createFormBuilder()
				->add('query', 'text', array('label' => 'Upis za pretraživanje', 'attr' => array('class' => 'pro3x_query')))->getForm();
		
		if($this->getRequest()->isMethod('post'))
		{
				$form->bind($this->getRequest());
				$data = $form->getData();

				$items = $this->getCustomerRepository()->createQueryBuilder('c')->andWhere('c.name LIKE :name OR c.taxNumber LIKE :name')->getQuery()->execute(array(':name' => '%' . $data['query'] . '%'));
		}
		else if($customerId != null)
		{
			$customer = $this->getCustomerRepository()->findOneById($customerId);
			$this->redirect404($customer);
			
			$invoice->setCustomer($customer);
			
			$manager = $this->getDoctrine()->getManager();
			$manager->persist($invoice);
			$manager->flush();
			
			return $this->redirect($this->getParam('back'));
		}
		else
		{
			$items = array();
		}

		return array('form' => $form->createView(), 'clients' => $items);
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
	 * @Route("/delete/{id}", name="delete_invoice")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getInvoiceRepository(), 'Račun je izbrisan');
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
				
				->addColumn('id', "Naziv")
				->addColumn('status', "Status")
				
				->setAddRoute('add_invoice')
				->setEditRoute('edit_invoice')
				->setDeleteRoute('delete_invoice')
				->setDeleteColumn('id')
				->setDeleteType('račun')
				
				->setPager(true)
				->setPageCount($this->getPageCount($count))
				->setPage($page)
				->setItems($items)->getParams();
    }
}
