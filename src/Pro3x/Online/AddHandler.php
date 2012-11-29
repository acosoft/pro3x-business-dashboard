<?php

namespace Pro3x\Online;

class AddHandler
{
	protected $controller;
	private $formType;
	private $successMessage;
	private $title;
	private $icon;
	
	function __construct($controller)
	{
		$this->controller = $controller;
	}

	public function getFormType()
	{
		return $this->formType;
	}

	public function setFormType($formType)
	{
		$this->formType = $formType;
		return $this;
	}

	public function getSuccessMessage()
	{
		return $this->successMessage;
	}

	public function setSuccessMessage($successMessage)
	{
		$this->successMessage = $successMessage;
		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	public function getIcon()
	{
		return $this->icon;
	}

	public function setIcon($icon)
	{
		$this->icon = $icon;
		return $this;
	}
	
	public function execute()
	{
		$form = $this->controller->createForm($this->getFormType());
		if($result = $this->controller->saveForm($form, $this->getSuccessMessage())) return $result;

		return $this->controller->editParams($form, $this->getTitle(), $this->getIcon());
	}
}

?>
