<?php

namespace Pro3x\AppBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api")
 */
class ApiController extends AdminController
{
	/**
	 * 
	 * @param \Pro3x\SecurityBundle\Entity\User $user
	 * @return string
	 */
	private function getToken($user)
	{
		return md5($user->getId());
	}
	
	public function decode($data)
	{
		return json_decode($data);
	}
	
	public function encode($data)
	{
		return json_encode($data);
	}
	
	/**
	 * @Route("/invoices/{id}/{positionId}/{token}", name="api_invoices")
	 * @Template()
	 */
	public function invoicesAction($id, $positionId, $token)
	{
		try
		{
			$user = $this->getUserRepository()->find($id);

			if ($this->getToken($user) == $token)
			{
				$position = $this->getPositionRepository()->find($positionId); /* @var $position \Pro3x\InvoiceBundle\Entity\Position */
				$data = $this->decode($this->getRequest()->getContent());

				$invoice = new \Pro3x\InvoiceBundle\Entity\Invoice();
				$invoice->setUser($user);
				$invoice->setPosition($position);
				
				$invoice->setStatus("Mobile");
				$template = $this->getTemplateRepository()->find(4);
				$invoice->setTemplate($template);
				
				$manager = $this->getDoctrine()->getEntityManager();
				
				foreach ($data->items as $item)
				{
					$product = $this->getProductRepository()->find($item->productId);
					$invoiceItem = new \Pro3x\InvoiceBundle\Entity\InvoiceItem($product);
					$invoiceItem->setAmount($item->quantity);
					$invoiceItem->setDiscount(0);
					$invoiceItem->calculate();
					
					$invoiceItem->setInvoice($invoice);
					$invoice->getItems()->add($invoiceItem);
					
					$manager->persist($invoiceItem);
				}
				
				$invoice->calculate();
				
				$invoice->setSequence($position->getSequence());
				$position->setSequence($position->getSequence() + 1);
				
				$manager->persist($position);
				$manager->persist($invoice);
				$manager->flush();
				
				//$this->finaInvoice($invoice);

				return new \Symfony\Component\HttpFoundation\Response($this->encode('OK'));
			}
		}
		catch (\Exception $exc)
		{
			return new \Symfony\Component\HttpFoundation\Response($this->encode(array('error' => "Iznimka u komunikaciji sa servisima")));
		}
	}
	
	/**
	 * @Route("/products/{id}/{token}", name="api_products")
	 */
	public function productsAction($id, $token)
	{
		$user = $this->getUserRepository()->find($id);
		
		if($this->getToken($user) == $token)
		{
			$response = array();
		
			foreach($this->getProductRepository()->findAll() as $product) /* @var $product \Pro3x\InvoiceBundle\Entity\Product */
			{
				$response[] = array('id' => $product->getId(),
					'name' => $product->getName(),
					'price' => $product->getTaxedPrice());
			}

			return new \Symfony\Component\HttpFoundation\Response($this->encode($response));
		}
	}
	
	/**
	 * @Route("/login", name="api_login")
	 */
	public function loginAction()
	{
		$data = $this->decode($this->getRequest()->getContent());
		
		$username = $data->username;
		$password = $data->password;
		
		$user = $this->getUserRepository()->findOneByUsername($username); /* @var $user \Pro3x\SecurityBundle\Entity\User */
		$position = $this->getPositionRepository()->find($user->getPosition()); /* @var $position \Pro3x\InvoiceBundle\Entity\Position */
		
		//TODO: exception handlin when user doesn't exist
		
		$response = array('fullName' => $user->getDisplayName(),
			'cashRegister' => $position->getDescription(),
			'cashRegisterId' => $position->getId(),
			'token' => $this->getToken($user),
			'userId' => $user->getId());
		
		return new \Symfony\Component\HttpFoundation\Response($this->encode($response));
	}
}

?>
