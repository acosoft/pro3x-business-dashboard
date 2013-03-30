<?php

namespace Pro3x\InvoiceBundle\Parsers;

class AmountCodeParser extends BaseParser
{
	public function parse($value)
	{
		$parts = array();
		if(preg_match('#(.*)\*(.*)#', $value, $parts) == 1)
		{
			$amount = $this->parseNumber($parts[1]);
			$this->setAmount($amount);
			$this->setCode($parts[2]);
			return true;
		}
		
		return false;
	}
}

?>
