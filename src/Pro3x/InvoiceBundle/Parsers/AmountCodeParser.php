<?php

namespace Pro3x\InvoiceBundle\Parsers;

class AmountCodeParser extends BaseParser
{
	public function parse($value)
	{
		$parts = array();
		if(preg_match('#(\d*)\*(.*)#', $value, $parts) == 1)
		{
			$this->setAmount($parts[1]);
			$this->setCode($parts[2]);
			return true;
		}
		
		return false;
	}
}

?>
