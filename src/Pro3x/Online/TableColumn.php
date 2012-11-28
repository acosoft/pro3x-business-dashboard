<?php

namespace Pro3x\Online;

class TableColumn
{
	private $name;
	private $width;
	private $label;
	private $textAlign;
	private $translate;
	
	function __construct($name, $label=null, $width = 0, $textAlign = 'left', $translate = false)
	{
		$this->name = $name;
		$this->width = $width;
		$this->textAlign = $textAlign;
		$this->translate = $translate;
		
		$this->label = ($label)?$label:$name;
	}
	
	public function getTranslate()
	{
		return $this->translate;
	}

	public function setTranslate($translate)
	{
		$this->translate = $translate;
	}
	
	public function getLabel()
	{
		return $this->label;
	}

	public function setLabel($label)
	{
		$this->label = $label;
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
	
	public function getTextAlign()
	{
		return $this->textAlign;
	}

	public function setTextAlign($textAlign)
	{
		$this->textAlign = $textAlign;
	}
}

?>
