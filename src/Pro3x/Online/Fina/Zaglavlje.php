<?php

namespace Pro3x\Online\Fina;

class Zaglavlje
{
	private $IdPoruke;
	private $DatumVrijeme;
	
	function __construct($IdPoruke = null)
	{
		$this->IdPoruke = $IdPoruke;
		$now = new \DateTime('now');
		$this->DatumVrijeme = $now->format('d.m.Y\TH:i:s');
	}

	public function getIdPoruke()
	{
		return $this->IdPoruke;
	}

	public function setIdPoruke($IdPoruke)
	{
		$this->IdPoruke = $IdPoruke;
	}

	public function getDatumVrijeme()
	{
		return $this->DatumVrijeme;
	}

}

?>
