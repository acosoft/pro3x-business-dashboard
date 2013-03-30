<?php

namespace Pro3x\InvoiceBundle\Parsers;

class CodeParser extends BaseParser
{
	public function parse($value)
	{
		if(preg_match('#\d*#', $value) == 1)
		{
			$this->setCode($value);
			return true;
		}
		
		return false;
	}
}

?>
