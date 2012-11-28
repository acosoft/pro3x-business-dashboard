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
		return 3;
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
	
	public function setWarningMessage($message)
	{
		$this->get('session')->setFlash('warning', $message);
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
