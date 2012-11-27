<?php

namespace Pro3x\InvoiceBundle\Parsers;

class BaseParser
{
	private $amount = 1;
	private $code;
	private $discount = 0;

	public function parse($value)
	{
		return true;
	}
	
	public function getAmount()
	{
		return $this->amount;
	}

	public function setAmount($amount)
	{
		$this->amount = $amount;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function setCode($code)
	{
		$this->code = $code;
	}

	public function getDiscount()
	{
		return $this->discount;
	}

	public function setDiscount($discount)
	{
		$this->discount = $discount;
	}
}

?>
