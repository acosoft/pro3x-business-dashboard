<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\InvoiceBundle\Form\ProductType;
use Pro3x\Online\TableParams;
use Symfony\Component\HttpFoundation\Response;
use Pro3x\InvoiceBundle\Entity\TaxRate;


class ProductController extends AdminController
{
	/**
	 * @Route("/admin/products/add")
	 * @Template("Pro3xInvoiceBundle:Product:addProduct.html.twig")
	 */
	public function addAction()
	{
		$form = $this->createForm(new ProductType, new \Pro3x\InvoiceBundle\Entity\Product());
		if($result = $this->saveForm($form, 'Novi proizvod je uspješno spremljen')) return $result;

		return $this->editParams($form, 'Novi proizvod', 'inventory_add');
	}
	
	/**
	 * @Route("/admin/products/calculate-total-price", name="calculate_total_price")
	 */
	public function calculateTotalPriceAction()
	{
		$basePrice = $this->parseNumber($this->getParam('amount', 0));
		$totalTaxRate = 0;
		
		$taxRates = $this->getRequest()->get('tax-rates', array());

		foreach ($taxRates as $id)
		{
			$tax = $this->getTaxRateRepository()->findOneById($id); /* @var $tax TaxRate */
			$totalTaxRate += $tax->getRate();
		}
		
		$taxedPrice = round($basePrice * (1 + $totalTaxRate), 2);
		$basePrice = $taxedPrice / (1 + $totalTaxRate);

		return new Response(json_encode(array(
			'amount' => $this->formatNumber($basePrice, 6),
			'tax' => $this->formatNumber($taxedPrice - $basePrice, 2, 6), 
			'total' => $this->formatNumber($taxedPrice, 2)), JSON_FORCE_OBJECT));
	}
	
	/**
	 * @Route("/admin/products/calculate-base-price", name="calculate_base_price")
	 */
	public function calculateBasePrice()
	{
		$taxedPrice = $this->parseNumber($this->getParam('amount', 0));
		$totalTaxRate = 0;
		
		$taxRates = $this->getRequest()->get('tax-rates', array());

		foreach ($taxRates as $id)
		{
			$tax = $this->getTaxRateRepository()->findOneById($id); /* @var $tax TaxRate */
			$totalTaxRate += $tax->getRate();
		}
		
		$basePrice = $taxedPrice / (1 + $totalTaxRate);

		return new Response(json_encode(array(
			'amount' => $this->formatNumber($basePrice, 6),
			'tax' => $this->formatNumber($taxedPrice - $basePrice, 2, 6), 
			'total' => $this->formatNumber($taxedPrice, 2)), JSON_FORCE_OBJECT));
	}
	
	
	/**
	 * @Route("/admin/products/edit/{id}")
	 * @Template("Pro3xInvoiceBundle:Product:addProduct.html.twig")
	 */
	public function editAction($id)
	{
		$product = $this->getProductRepository()->findOneById($id);
		$this->redirect404($product);
		
		$form = $this->createForm(new ProductType(), $product);
		if($result = $this->saveForm($form, 'Proizvod je uspješno izmjenjen')) return $result;
		
		return $this->editParams($form, 'Izmjena proizvoda', 'inventory_edit');
	}
	
	/**
	 * @Route("/admin/products/delete/{id}")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getProductRepository(), 'Proizvod je uspješno izbrisan');
	}
	
	/**
     * @Route("/admin/products/{page}", name="products", defaults={"page" = 1})
     * @Template("::table.html.twig")
     */
    public function indexAction($page)
    {
		$params = new TableParams();
		
		$repository = $this->getProductRepository();
		$count = $repository->count();
		
		$products = $this->getProductRepository()->findBy(array(), array('name' => 'ASC'), $this->getPageSize(), $this->getPageOffset($page)); /* @var $products \Doctrine\Common\Collections\ArrayCollection */
		
		$numeric = $this->getNumeric();
		foreach ($products as $product) /* @var $product \Pro3x\InvoiceBundle\Entity\Product */
		{
			$product->setNumeric($numeric);
		}
		
		return $params->setTitle('Popis proizvoda')
				->setIcon('inventory')
				
				->addColumn('barcode', 'Barkod')
				->addColumn('name', 'Naziv')
				->addColumn("Unit", "Jedinica")
				->addColumn('taxedInputPrice', 'Nabavna', 0, 'right')
				->addColumn('taxedPriceFormated', 'Prodajna', 0, 'right')
				
				
				->setToolsWidth(150)
				
				->setAddRoute('pro3x_invoice_product_add')
				->setEditRoute('pro3x_invoice_product_edit')
				->setDeleteRoute('pro3x_invoice_product_delete')
				->setDeleteColumn('name')
				->setDeleteType('proizvod ili uslugu')
				
				->setPagerVisible(true)
				->setPageCount($this->getPageCount($count))
				->setPage($this->getRequest()->get('page', 1))
				->setItems($products)->getParams();
    }
}
