<?php

namespace Pro3x\Online\Fina;

class Greska
{
	private $SifraGreske;
	private $PorukaGreske;
	
	public function getSifraGreske()
	{
		return $this->SifraGreske;
	}

	public function setSifraGreske($SifraGreske)
	{
		$this->SifraGreske = $SifraGreske;
	}

	public function getPorukaGreske()
	{
		return $this->PorukaGreske;
	}

	public function setPorukaGreske($PorukaGreske)
	{
		$this->PorukaGreske = $PorukaGreske;
	}
}

?>
