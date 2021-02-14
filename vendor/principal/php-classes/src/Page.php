<?php

namespace Principal;
use Rain\Tpl;

class Page {

	private $tpl;
	private $options = [];
	private $defaults = [
		"header"=>true,
		"footer"=>true,
		"data"=>[]
	];

	public function __construct($opts = array(), $tpl_dir = "/views/")
	{

		$this->options = array_merge($this->defaults,$opts);

		$config = array(
		    "base_url"      => null,
		    "tpl_dir"       => $_SERVER['DOCUMENT_ROOT'].$tpl_dir, //diretorio dos templetes HTML
	    	"cache_dir"     => $_SERVER['DOCUMENT_ROOT']."/views-cache/", //diretorio dos mmergers HTML/PHP
	    	"debug"         => false
		);

		Tpl::configure( $config );

		$this->tpl = new Tpl();

		if ($this->options['data']) $this->setData($this->options['data']);

		if ($this->options['header'] === true) $this->tpl->draw("header");

	}

	public function __destruct()
	{

		if ($this->options['footer'] === true) $this->tpl->draw("footer");

	}

	private function setData($data = array())
	{

		foreach($data as $key => $val)
		{

			$this->tpl->assign($key, $val);

		}

	}

	public function setTpl($name, $data = array(), $returnHTML = false)
	{

		$this->setData($data);

		return $this->tpl->draw($name, $returnHTML);

	}

}

?>