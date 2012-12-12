<?php

namespace Pro3x\InvoiceBundle\Parsers;

class AmountCodePercentParser extends BaseParser
{
	public function parse($value)
	{
		$parts = array();
		if(preg_match('#(\d*)\*(.*)-(.*)#', $value, $parts))
		{
			$this->setAmount($parts[1]);
			$this->setCode($parts[2]);
			$this->setDiscount($parts[3] / 100);
			
			return true;
		}
		
		return false;
	}
}

?>
