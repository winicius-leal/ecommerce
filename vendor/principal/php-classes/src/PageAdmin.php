<?php

namespace Principal;

/**
 * 
 */
class PageAdmin extends Page
{
	
	function __construct($opts = array(), $tpl_dir = "/views/admin/")
	{
		
		parent::__construct($opts, $tpl_dir); //chama o metodo construct da classe page que esta sendo extendida
	}
}


?>