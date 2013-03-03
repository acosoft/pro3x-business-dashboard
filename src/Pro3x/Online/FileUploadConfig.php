<?php

namespace Pro3x\Online;

class FileUploadConfig
{
	private $dir;
	private $url;
	
	function __construct($dir, $url)
	{
		$this->dir = $dir;
		$this->url = $url;
	}

	public function getDir()
	{
		return $this->dir;
	}
	
	public function getUrl($filename)
	{
		if($filename != null)
			return $this->url . $filename;
		else 
			return null;
	}
}

?>
