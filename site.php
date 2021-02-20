<?php
use \Principal\Page;

$app->get('/', function () {
	$page = new Page();
	$page->setTpl("index");

});

?>