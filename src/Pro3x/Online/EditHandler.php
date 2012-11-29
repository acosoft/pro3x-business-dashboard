<?php

namespace Pro3x\Online;

class EditHandler extends AddHandler
{
	private $repository;
	private $id;
	
	function __construct($controller, $itemId)
	{
		parent::__construct($controller);
		$this->id = $itemId;
	}

	
	public function getRepository()
	{
		return $this->repository;
	}

	public function setRepository($repository)
	{
		$this->repository = $repository;
		return $this;
	}

	public function execute()
	{
		$item = $this->getRepository()->findOneById($this->id);
		$this->controller->redirect404($item);
		
		$form = $this->controller->createForm($this->getFormType(), $item);
		
		if($result = $this->controller->saveForm($form, $this->getSuccessMessage()))
			return $result;
		else
			return $this->controller->editParams($form, $this->getTitle(), $this->getIcon());
	}
}

?>
