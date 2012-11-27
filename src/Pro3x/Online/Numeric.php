<?php

namespace Pro3x\Online;

class Numeric
{
	private $locale;
	
	function __construct($locale)
	{
		$this->locale = $locale;
	}
	
	public function getLocale()
	{
		return $this->locale;
	}

	public function setLocale($locale)
	{
		$this->locale = $locale;
	}

	public function getNumberFormatter($minDecimals = 2, $maxDecimals = null)
	{
		$nf = new \NumberFormatter($this->getLocale(), \NumberFormatter::DECIMAL);
		
		if(!$maxDecimals)
			$maxDecimals = $minDecimals;
		
		$nf->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $minDecimals);
		$nf->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $maxDecimals);
		$nf->setAttribute(\NumberFormatter::GROUPING_USED, false);
		
		return $nf;
	}
}

?>
