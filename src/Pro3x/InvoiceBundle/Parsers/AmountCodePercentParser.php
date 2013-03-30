<?php

namespace Pro3x\InvoiceBundle\Parsers;

class AmountCodePercentParser extends BaseParser
{
	function __construct($controller)
	{
		parent::__construct($controller);
	}

	public function parse($value)
	{
		$parts = array();
		if(preg_match('#(.*)\*(.*)-(.*)%{0,1}#', $value, $parts))
		{
			$amount = $this->parseNumber($parts[1]);
			$this->setAmount($amount);
			
			$this->setCode($parts[2]);
			$this->setDiscount($parts[3] / 100);
			
			return true;
		}
		
		return false;
	}
}

?>
