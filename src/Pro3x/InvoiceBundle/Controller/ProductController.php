<?php

namespace Pro3x\InvoiceBundle\Controller;

use Pro3x\AppBundle\Controller\AdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pro3x\InvoiceBundle\Form\ProductType;
use Pro3x\Online\TableParams;
use Symfony\Component\HttpFoundation\Response;
use Pro3x\InvoiceBundle\Entity\TaxRate;

/**
 * @Route("/admin/products")
 */
class ProductController extends AdminController
{
	/**
	 * @Route("/add")
	 * @Template("Pro3xInvoiceBundle:Product:addProduct.html.twig")
	 */
	public function addAction()
	{
		$form = $this->createForm(new ProductType);
		if($result = $this->saveForm($form, 'Novi artikal je uspješno spremljen')) return $result;

		return $this->editParams($form, 'Novi artikal', 'inventory_add');
	}
	
	/**
	 * @Route("/calculate-total-price", name="calculate_total_price")
	 */
	public function calculateTotalPriceAction()
	{
		$basePrice = $this->getParam('amount', 0);
		$totalTaxRate = 0;
		
		$taxRates = $this->getRequest()->get('tax-rates', array());

		foreach ($taxRates as $id)
		{
			$tax = $this->getTaxRateRepository()->findOneById($id); /* @var $tax TaxRate */
			$totalTaxRate += $tax->getRate();
		}
		
		$taxedPrice = $basePrice * (1 + $totalTaxRate);

		return new Response(json_encode(array(
			'amount' => number_format($basePrice, 6),
			'tax' => number_format($taxedPrice - $basePrice, 2), 
			'total' => number_format($taxedPrice, 2)), JSON_FORCE_OBJECT));
	}
	
	/**
	 * @Route("/calculate-base-price", name="calculate_base_price")
	 */
	public function calculateBasePrice()
	{
		$taxedPrice = $this->getParam('amount', 0);
		$totalTaxRate = 0;
		
		$taxRates = $this->getRequest()->get('tax-rates', array());

		foreach ($taxRates as $id)
		{
			$tax = $this->getTaxRateRepository()->findOneById($id); /* @var $tax TaxRate */
			$totalTaxRate += $tax->getRate();
		}
		
		$basePrice = $taxedPrice / (1 + $totalTaxRate);

		return new Response(json_encode(array(
			'amount' => number_format($basePrice, 6),
			'tax' => number_format($taxedPrice - $basePrice, 2), 
			'total' => number_format($taxedPrice, 2)), JSON_FORCE_OBJECT));
	}
	
	
	/**
	 * @Route("/edit/{id}")
	 * @Template("Pro3xInvoiceBundle:Product:addProduct.html.twig")
	 */
	public function editAction($id)
	{
		$product = $this->getProductRepository()->findOneById($id);
		$this->redirect404($product);
		
		$form = $this->createForm(new ProductType(), $product);
		if($result = $this->saveForm($form, 'Proizvod je uspješno izmjenjen')) return $result;
		
		return $this->editParams($form, 'Edit product', 'inventory_edit');
	}
	
	/**
	 * @Route("/delete/{id}")
	 * @Template()
	 */
	public function deleteAction()
	{
		return $this->deleteEntity($this->getProductRepository(), 'Proizvod je uspješno izbrisan');
	}
	
	/**
     * @Route("/{page}", name="products", defaults={"page" = 1})
     * @Template("::table.html.twig")
     */
    public function indexAction($page)
    {
		$params = new TableParams();
		
		$repository = $this->getProductRepository();
		$count = $repository->count();
		
		return $params->setTitle('Popis proizvoda')
				->setIcon('inventory')
				
				->addColumn('barcode', 'Barkod')
				->addColumn('name', 'Naziv')
				->addColumn('unitPriceFormated', 'Jedinična cijena')
				->addColumn('taxAmountFormated', 'Iznos poreza')
				->addColumn('taxedPriceFormated', 'MPC')
				->addColumn("Unit", "Jedinica")
				
				->setToolsWidth(200)
				
				->setAddRoute('pro3x_invoice_product_add')
				->setEditRoute('pro3x_invoice_product_edit')
				->setDeleteRoute('pro3x_invoice_product_delete')
				->setDeleteColumn('name')
				->setDeleteType('poreznu stopu')
				
				->setPager(true)
				->setPageCount($this->getPageCount($count))
				->setItems($this->getProductRepository()->findBy(array(), array('name' => 'ASC'), $this->getPageSize(), $this->getPageOffset($page)))->getParams();
    }
}
