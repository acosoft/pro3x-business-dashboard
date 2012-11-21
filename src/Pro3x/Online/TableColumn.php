<?php

namespace Pro3x\Online;

class TableColumn
{
	private $name;
	private $width;
	
	function __construct($name, $width = 0)
	{
		$this->name = $name;
		$this->width = $width;
	}
	
	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function setWidth($width)
	{
		$this->width = $width;
	}
}

?>
