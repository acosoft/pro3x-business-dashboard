<?php

namespace Pro3x\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\EntityRepository;
use Pro3x\Online\Numeric;

class AdminController extends Controller
{
	public function getPageSize()
	{
		return 10;
	}
	
	public function getPageOffset($page)
	{
		return ($page - 1) * $this->getPageSize();
	}
	
	public function getPageCount($itemCount)
	{
		if($itemCount == 0)
			return 1;
		else
			return ceil($itemCount / $this->getPageSize());
	}
	
	public function setMessage($message)
	{
		$this->get('session')->setFlash('message', $message);
	}
	
	public function setWarning($message)
	{
		$this->get('session')->setFlash('warning', $message);
	}
	
	public function goBack()
	{
		return $this->redirect($this->getBackUrl());
	}
	
	public function goHome()
	{
		return $this->redirect($this->generateUrl('dashboard'));
	}
	
	public function redirect404($test)
	{
		if(!$test)
		{
			$this->createNotFoundException();
		}
	}
	
	public function getNumeric()
	{
		return new Numeric($this->getLocale());
	}
	
	public function getConfigParam($name)
	{
		return $this->container->getParameter($name);
	}
	
	/**
	 * 
	 * @return \Pro3x\Online\FinaClientFactory
	 */
	public function getFinaClientFactory()
	{
		return $this->get('fina.client.factory');
	}
	
	/**
	 * 
	 * @return \Pro3x\Online\FileUploadConfig
	 */
	public function getFileUploadConfig()
	{
		return $this->get('file.upload.config');
	}
	
	/**
	 * 
	 * @return \NumberFormatter
	 */
	public function getNumberFormatter($minDecimals, $maxDecimals = null)
	{
		$numeric = new \Pro3x\Online\Numeric($this->getLocale());
		
		if(!$maxDecimals)
			$maxDecimals = $minDecimals;
		
		return $numeric->getNumberFormatter($minDecimals, $maxDecimals);
	}
	
	public function parseNumber($value)
	{
		return $this->getNumberFormatter(2)->parse($value);
	}
	
	public function formatNumber($value, $minDecimals = 2, $maxDecimals = null)
	{
		if(!$maxDecimals) $maxDecimals = $minDecimals;
		return $this->getNumberFormatter($minDecimals, $maxDecimals)->format($value);
	}
	
	public function getParam($name, $default = null)
	{
		return $this->getRequest()->get($name, $default);
	}
	
	/**
	 * 
	 * @return \Symfony\Component\HttpFoundation\Session\Session
	 */
	public function getSession()
	{
		return $this->get('session');
	}
	
	public function getLocale()
	{
		return $this->getRequest()->getLocale();
	}
	
	public function editParams($form, $title, $icon)
	{
		return array('form' => $form->createView(), 'title' => $title, 'cssClass' => 'pro3x_small_icon_' . $icon);
	}
	
	public function tableParams($items, $page, $count)
	{
		return array('items' => $items, 'page' => $page, 'count' => $count);
	}
		
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\CustomerRepository
	 */
	public function getCustomerRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:Customer');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\NoteRepository
	 */
	public function getNoteRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:Note');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\CustomerRelationRepository
	 */
	public function getRelationsRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:CustomerRelation');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\RelationType
	 */
	public function getRelationTypeRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:RelationType');
	}
	
	public function getRegistrationKeysRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xRegistrationKeysBundle:RegistrationKey');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\PositionRepository
	 */
	public function getPositionRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:Position');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\ShopRepository
	 */
	public function getLocationRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:Location');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\DailySalesReportRepository
	 */
	public function getDailyReportRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:DailySalesReport');
	}
	
	public function getUserRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xSecurityBundle:User');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\InvoiceRepository
	 */
	public function getInvoiceRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:Invoice');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\InvoiceItem
	 */
	public function getInvoiceItemRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:InvoiceItem');
	}
		
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\TaxRateRepository
	 */
	public function getTaxRateRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:TaxRate');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\ProductRepository
	 */
	public function getProductRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:Product');
	}
	
	/**
	 * 
	 * @return \Pro3x\InvoiceBundle\Entity\TemplateRepository
	 */
	public function getTemplateRepository()
	{
		return $this->getDoctrine()->getRepository('Pro3xInvoiceBundle:Template');
	}
	
	protected function finaInvoice(\Pro3x\InvoiceBundle\Entity\Invoice $invoice)
	{
		try
		{
			if(!$invoice->isFiscalTransaction()) return;
			
			$location = $invoice->getPosition()->getLocation();
			
			if ($location->getCompanyTaxNumber() && $this->getFinaClientFactory()->isFiscalTransaction($invoice->getTemplate()->getTransactionType()))
			{
				if($invoice->getUniqueInvoiceNumber() == null)
				{
					$location = $invoice->getPosition()->getLocation();
					$soap = $this->getFinaClientFactory()->createInstance($location->getSecurityKey(), $location->getSecurityCertificate(), array('trace' => true));

					if (!$invoice->getUuid())
					{
						$invoice->setUuid($soap->randomGuid());

						$manager = $this->getDoctrine()->getEntityManager();
						$manager->persist($invoice);
						$manager->flush();

						$repeatedMessage = false;
					}
					else
					{
						$repeatedMessage = true;
					}

					$zahtjev = new \Pro3x\Online\Fina\RacunZahtjev($invoice->getUuid());

					$racun = $zahtjev->getRacun();
					$racun->setOib($invoice->getPosition()->getLocation()->getCompanyTaxNumber());
					$racun->setUSustPdv($invoice->getPosition()->getLocation()->getTaxPayer());
					$racun->setDatVrijeme($invoice->getCreated()->format('d.m.Y\TH:i:s'));
					$racun->setOznSlijed('N');

					$oznaka = $racun->getBrRac();
					$oznaka->setBrOznRac($invoice->getSequence());
					$oznaka->setOznPosPr($invoice->getPosition()->getLocationName());
					$oznaka->setOznNapUr($invoice->getPosition()->getName());

					if ($invoice->getPosition()->getLocation()->getTaxPayer())
					{
						$map = array('Pdv' => array(), 'Pnp' => array());

						$invoice->setNumeric($this->getNumeric());
						foreach ($invoice->getTaxItems() as $item) /* @var $item \Pro3x\InvoiceBundle\Entity\InvoiceItemTax */
						{
							$porez = new \Pro3x\Online\Fina\Porez();

							$porez->setOsnovica(number_format(round($item['baseNumeric'], 2, PHP_ROUND_HALF_DOWN), 2, '.', ''));
							$porez->setStopa(number_format($item['rateNumeric'], 2));
							$porez->setIznos(number_format(round($item['amountNumeric'], 2, PHP_ROUND_HALF_DOWN), 2, '.', ''));

							$map[$item['group']][] = $porez;
						}

						if (count($map['Pdv']) > 0)
							$racun->setPdv($map['Pdv']);

						if (count($map['Pnp']) > 0)
							$racun->setPnp($map['Pnp']);
					}
					else
					{
						$racun->setPdv(null);
						$racun->setPnp(null);
					}

					$racun->setIznosUkupno($invoice->getTotal());
					$racun->setNacinPlac($invoice->getTemplate()->getTransactionType());
					$racun->setOibOper($invoice->getUser()->getOib());
					$racun->setNakDost($repeatedMessage);
					
					if($invoice->getOriginalInvoiceNumber())
					{
						$racun->setParagonBrRac($invoice->getOriginalInvoiceNumber());
					}

					try
					{
						$data = $soap->racuni($zahtjev); /* @var $data \Pro3x\Online\Fina\RacunOdgovor */
					}
					catch(\Exception $exc)
					{
						$data = null;
						$this->setWarning('Iznimka u komunikacija sa servisima: ' . $exc->getMessage());
					}
					
					if(!$invoice->getCompanySecureCode())
					{
						$invoice->setCompanySecureCode($zahtjev->getRacun()->getZastKod());
					}
					
					if ($data instanceof \Pro3x\Online\Fina\RacunOdgovor && !$invoice->getUniqueInvoiceNumber())
					{
						$invoice->setUniqueInvoiceNumber($data->getJir());

						$manager = $this->getDoctrine()->getEntityManager();
						$manager->persist($invoice);
						$manager->flush();
					}
				}
			}
			else
			{
				$invoice->setFiscalTransaction(false);
				
				$manager = $this->getDoctrine()->getEntityManager();
				$manager->persist($invoice);
				$manager->flush();
			}
		}
		catch (\Exception $exc) /* @var $exc \Exception */
		{
			$this->setWarning('Iznimka u komunikacija sa servisima: ' . $exc->getMessage());
		}
	}
	
	public function deleteEntity($repository, $message)
	{
		$id = $this->getRequest()->get('id');
		
		if($repository instanceof EntityRepository)
		{
			$group = $repository->findOneById($id);
		}
		else
		{
			$group = $this->getDoctrine()->getRepository($repository)->findOneById($id);
		}
		
		$this->redirect404($group);
		
		$manager = $this->getDoctrine()->getEntityManager();
		$manager->remove($group);
		$manager->flush();
		
		$this->get('session')->setFlash('message', $message);
		return $this->redirect($this->getRequest()->get('back'));
	}
	
	public function getBackUrl()
	{
		if($back = $this->getRequest()->get('back'))
		{
			return $back;
		}
		else
		{
			return $this->generateUrl('dashboard');
		}
	}
	
	public function isInRole($role)
	{
		return $this->get('security.context')->isGranted('ROLE_' . strtoupper($role));
	}
	
	public function isPost()
	{
		return $this->getRequest()->isMethod("POST");
	}
	
	public function saveForm($form, $message)
	{
		if($this->getRequest()->isMethod('post'))
		{
			$form->bind($this->getRequest());
			
			if($form->isValid())
			{
				$manager = $this->getDoctrine()->getEntityManager();
				$manager->persist($form->getData());
				$manager->flush();
				
				$this->get('session')->setFlash('message', $message);
				return $this->redirect($this->getBackUrl());
			}
		}
		
		return false;
	}
	
	public function translate($message, $params = array())
	{
		return $this->get('translator')->trans($message, $params);
	}
}
