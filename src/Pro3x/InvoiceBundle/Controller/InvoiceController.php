<?php

namespace Pro3x\InvoiceBundle\Controller;

use \Pro3x\InvoiceBundle\Form\TemplateType as TemplateType;

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
	 * @Route("/add/{customer}", name="add_invoice", defaults={"customer" = null})
	 * @Template("Pro3xInvoiceBundle:Invoice:invoice.html.twig")
	 */
	public function addAction($customer)
	{
		$invoice = new Invoice();
		$invoice->setStatus('skica');
		
		$invoice->setUser($this->getUser());
		
		if($this->getUser()->getPosition() == null)
		{
			$this->setWarning('Vaš korisnički račun nije povezan sa niti jednom lokacijom, prije izdavanja računa morate izabrati lokaciju na kojoj ćete raditi');
			return $this->goBack();
		}
		
		if($customer != null)
		{
			$customer = $this->getCustomerRepository()->find($customer);
			$invoice->setCustomer($customer);
		}
		
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
			
			if(strlen($query) < 3) $this->setWarning('Potrebno je minimalno tri znaka za uspješno pretraživanje');
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
				->setToolsTemplate('Pro3xInvoiceBundle:Invoice:invoiceCustomerTools.html.twig')
				->setItems($items)->getParams();
	}
	
	private function addInvoiceItem($invoice, $product, $amount, $discount)
	{
		$item = new InvoiceItem($product);
		$item->setNumeric($this->getNumeric());
		$item->setAmount($amount);
		$item->setDiscount($discount);
		$item->setInvoice($invoice);

		$item->calculate();

		$invoice->getItems()->add($item);
		$invoice->calculate();

		$manager = $this->getDoctrine()->getEntityManager();
		$manager->persist($item);
		$manager->flush();
		
		$itemView = $this->renderView('Pro3xInvoiceBundle:Invoice:addItem.html.twig', array('item' => $item));

		return new Response(json_encode(array(
			'total' => $this->formatNumber($invoice->getTotal(), 2),
			'item' => $itemView), JSON_FORCE_OBJECT));
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
				$found = true;
				break;
			}
		}
		
		$product = $this->getProductRepository()->findOneByBarcode($parser->getCode()); /* @var $product Product */
		
		if($product)
		{
			return $this->addInvoiceItem($invoice, $product, $parser->getAmount(), $parser->getDiscount());
		}
		else
		{
			$items = $this->getProductRepository()->search($parser->getCode());
			if(count($items) == 1)
			{
				return $this->addInvoiceItem($invoice, $items[0], $parser->getAmount(), $parser->getDiscount());
			}
			else
			{
				$jsonItems = array();
				
				foreach($items as $item) /* @var $item Product */
				{
					$item->setNumeric($this->getNumeric());
					$jsonItems[] = array('name' => $item->getName(), 'code' => $item->getCode(), 'price' => $item->getTaxedPriceFormated());
				}
				
				$jsonData = array('search' => $data, 'amount' => $parser->getAmount(), 'discount' => $parser->getDiscount() * 100, 'items' => $jsonItems);
				return new \Symfony\Component\HttpFoundation\JsonResponse($jsonData);
			}
		}
	}
	
	/**
	 * @Route("/edit/{id}", name="edit_invoice")
	 * @Template("Pro3xInvoiceBundle:Invoice:invoice.html.twig")
	 */
	public function editAction($id)
	{
		$invoice = $this->getInvoiceRepository()->findOneById($id); /* @var $invoice Invoice */
		
		if($invoice->getPosition()->getLocation()->getTemplates()->count() == 0)
		{
			$this->setWarning('Vaša lokacija nije povezana sa niti jednim predloškom računa. Morate definirati barem jedan predložak računa za lokaciju prije izdavanja računa.');
			return $this->goBack();
		}
		
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
		
		$type = $this->getParam("type", "auto");
		
		$invoice->setNumeric($this->getNumeric());
		
		if($invoice->getSequence() == null)
		{
			$templateId = $this->getParam('mode');
			$template = $this->getTemplateRepository()->find($templateId); /* @var $template \Pro3x\InvoiceBundle\Entity\Template */
			$this->redirect404($template);
		
			if(\Pro3x\InvoiceBundle\Form\TemplateType::isTenderTransaction($template->getTransactionType()))
			{
				if($type == 'auto') $type = 'tender';
				
				if(!$invoice->getTenderSequence())
				{
					$position = $invoice->getPosition();
					$invoice->setTenderSequence($position->getTenderSequence());
					$position->setTenderSequence($position->getTenderSequence() + 1);

					$invoice->setTenderDate(new \DateTime('now'));
					$invoice->setTenderTemplate($template);
					$invoice->setStatus($template->getName());

					$manager = $this->getDoctrine()->getEntityManager();

					$manager->persist($invoice);
					$manager->persist($position);

					$manager->flush();
				}
			}
			else if(!\Pro3x\InvoiceBundle\Form\TemplateType::isTenderTransaction($template->getTransactionType()))
			{
				if($type == 'auto') $type = 'invoice';
				$invoice->setFiscalTransaction($this->getFinaClientFactory()->isFiscalTransaction($template->getTransactionType()));

				$this->getDoctrine()->getEntityManager()->transactional(function($manager) use ($invoice, $template) {
					$position = $invoice->getPosition(); /* @var $position Pro3x\InvoiceBundle\Entity\Position */

					$manager->refresh($position);

					$invoice->setSequence($position->getSequence());
					$position->setSequence($position->getSequence() + 1);
					$invoice->setStatus($template->getName());
					$invoice->setTemplate($template);
					$invoice->setCreated(new \DateTime('now'));

					$manager->persist($position);
					$manager->persist($invoice);
					$manager->flush();
				});
			}
		}
		
		$this->finaInvoice($invoice);
		
		foreach($invoice->getItems() as $item) /* @var $item InvoiceItem */
		{
			$item->setNumeric($invoice->getNumeric());
		}
		
		if($type == "invoice" || $invoice->getTenderTemplate() == null)
			$print = $this->renderView('Pro3xInvoiceBundle:Invoice:print-' . $invoice->getTemplate()->getFilename() . '.html.twig', array('hello' => 'Hello Google Cloud Print : )', 'invoice' => $invoice));
		else
			$print = $this->renderView('Pro3xInvoiceBundle:Invoice:print-' . $invoice->getTenderTemplate()->getFilename() . '.html.twig', array('hello' => 'Hello Google Cloud Print : )', 'invoice' => $invoice));
		
		
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
	public function deleteAction($id)
	{
		$invoice = $this->getInvoiceRepository()->find($id); /* @var $invoice Invoice */
		
		if($invoice->getSequence() == null || $this->isInRole('delete_invoice'))
		{
			$manager = $this->getDoctrine()->getEntityManager();
			$manager->remove($invoice);
			$manager->flush();
			
			$this->setMessage("Račun je izbrisan");
		}
		else
		{
			$this->setWarning("Nemate dozvolu za brisanje ispisanih računa");
		}
		
		return $this->goBack();
		
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
		
		return $this->goBack();
	}
	
	/**
	 * @Route("/delete-invoice-item/{id}", name="delete_invoice_item")
	 */
	public function deleteItemAction($id)
	{
		$item = $this->getInvoiceItemRepository()->findOneById($id); /* @var $item InvoiceItem */
		$invoice = $item->getInvoice(); /* @var $invoice Invoice */
		
		$invoice->getItems()->removeElement($item);
		$invoice->calculate();
		
		$manager = $this->getDoctrine()->getManager();
		$manager->remove($item);
		$manager->flush();
		
		return new Response(json_encode(array('result' => true, 'total' => $this->formatNumber($invoice->getTotal(), 2)), JSON_FORCE_OBJECT));
	}
	
	/**
     * @Route("/{id}/{page}", name="invoices", defaults={"page" = 1})
     * @Template("::table.html.twig")
     */
    public function indexAction($id, $page)
    {		
		if($this->isInRole('edit_all_invoices') || $id == $this->getUser()->getId())
		{
			$params = new TableParams();
			$page = $this->getRequest()->get('page', 1);

			$repository = $this->getInvoiceRepository(); 
			
			
			//$params->addColumn('sequenceFormated', 'ID');
			$params->addTemplateColumn('Id', 'Pro3xInvoiceBundle:Invoice:idColumn.html.twig');
			
			if($this->isInRole('edit_all_invoices'))
			{
				$count = $this->getInvoiceRepository()->count();
				
				if($this->getUser()->getId() == $id)
				{
					$queryParams = array();
				}
				else
				{
					$queryParams = array('user' => $id);
				}
				
				$params
					->addTemplateColumn('Info', 'Pro3xInvoiceBundle:Invoice:infoColumn.html.twig')
					->addTemplateColumn('Kupac', 'Pro3xInvoiceBundle:Invoice:customerColumn.html.twig')
					->addTemplateColumn('Iznos', 'Pro3xInvoiceBundle:Invoice:totalColumn.html.twig');
				
				$showPosition = false;
			}
			else
			{
				$count = $repository->countByUser($id);
				
				$params
					->addColumn('dateTimeFormated', 'Datum', 125, 'center')
					->addColumn('customer.name', 'Kupac')
					->addColumnTrans('status', "Status")
					->addColumn('invoiceTotal', 'Ukupno', '75', 'right');
				
				$viewUser = $this->getUserRepository()->find($id);
				$queryParams = array('user' => $id, 'position' => $viewUser->getPosition());
				$params->setTitleExtraTemplate('Pro3xInvoiceBundle:Invoice:invoiceTitle.html.twig', array('user' => $this->getUserRepository()->find($id)));
				
				$showPosition = true;
			}
			
			$items = $repository->findBy($queryParams, array('id' => 'DESC'), $this->getPageSize(), $this->getPageOffset($page));

			$viewParams = $params->setTitle('Popis računa')
					->setIcon('invoice')

					->setAddRoute('add_invoice')
					->setEditRoute('edit_invoice')
					->setDeleteRoute('delete_invoice')
					->setDeleteColumn('sequenceFormated')
					->setDeleteType('račun')

					//->setToolsTemplate('Pro3xInvoiceBundle:Invoice:toolsColumn.html.twig')
					
					->setPagerVisible(true)
					->setPageCount($this->getPageCount($count))
					->setPage($page)
					->addPagerParam('id', $id)
					->setItems($items)->getParams();
			
			if($showPosition)
			{
				if($viewUser->getPosition())
				{
					$position = $this->getPositionRepository()->find($viewUser->getPosition());

					if($position)
						$viewParams['position'] = $position;
				}
				else
				{
					$this->setWarning('Korisnik nije povezan sa niti jednom lokacijom, prije pregleda izdanih računa morate odabari lokaciju');
					return $this->goBack();
				}
			}
			
			return $viewParams;
		}
		else
		{
			$this->setWarning('Nemate dozvole za rad sa računima ostalih korisnika');
			return $this->redirect($this->generateUrl('dashboard'));
		}
    }
}
