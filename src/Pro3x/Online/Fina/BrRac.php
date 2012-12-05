<?php

namespace Pro3x\Online\Fina;

class BrRac
{
	private $BrOznRac;
	private $OznPosPr;
	private $OznNapUr;
	
	public function getBrOznRac()
	{
		return $this->BrOznRac;
	}

	public function setBrOznRac($BrOznRac)
	{
		$this->BrOznRac = $BrOznRac;
	}

	public function getOznPosPr()
	{
		return $this->OznPosPr;
	}

	public function setOznPosPr($OznPosPr)
	{
		$this->OznPosPr = $OznPosPr;
	}

	public function getOznNapUr()
	{
		return $this->OznNapUr;
	}

	public function setOznNapUr($OznNapUr)
	{
		$this->OznNapUr = $OznNapUr;
	}
}

?>
