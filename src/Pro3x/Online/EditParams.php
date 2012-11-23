<?php

class EditParams
{
	private $form = null;
	private $extendTemplate = null;
	private $title;
	private $extensionParams;
	
	function __construct($form, $title)
	{
		$this->form = $form;
		$this->title = $title;
		$this->extensionParams = array();
	}

	public function getForm()
	{
		return $this->form;
	}

	public function setForm($form)
	{
		$this->form = $form;
		return $this;
	}

	public function getPluginTemplate()
	{
		return $this->extendTemplate;
	}

	public function setPluginTemplate($extendTemplate)
	{
		$this->extendTemplate = $extendTemplate;
		return $this;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getPluginParams()
	{
		return $this->extensionParams;
	}

	public function setPluginParams($extensionParams)
	{
		$this->extensionParams = $extensionParams;
		return $this;
	}

	public function getParams()
	{
		$params = array('form' => $this->form, 'title' => $this->title);
		
		if($this->getPluginTemplate())
		{
			$params['extensionTemplate'] = $this->getPluginTemplate();
			$params['params'] = $this->getPluginParams();
		}
		
		return $params;
	}
}

?>
