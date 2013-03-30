<?php

namespace Pro3x\InvoiceBundle\Parsers;

class CodePercentParser extends BaseParser
{
	public function parse($value)
	{
		$parts = array();
		if(preg_match('#(.*)-(.*)%{0,1}#', $value, $parts))
		{
			$this->setAmount(1);
			$this->setCode($parts[1]);
			$this->setDiscount($parts[2] / 100);
			
			return true;
		}
		
		return false;
	}
}

?>
