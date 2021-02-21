<?php
use \Principal\Page;
use \Principal\Model\Product;
use \Principal\Model\Category;

$app->get('/', function () {

	$products = Product::listAll();
	$page = new Page();
	
	$page->setTpl("index", array("products"=>Product::checkList($products)));

});


$app->get('/categories/:idcategory', function ($idcategory) {

	$page = (isset($_GET["page"])) ? (int)$_GET["page"] : 1;

	$category = new Category();

	$category->get((int)$idcategory); //coloca no obj o resultado da busca

	$pagination = $category->getProductsPage($page);

	$pages=[];

	for ($i=1; $i <= $pagination["pages"]; $i++) {
		array_push($pages, array(
			"link"=>"/categories/".$category->getidcategory()."?page=".$i, 
			"page"=>$i
		));
	}

	$page = new Page();

	$page->setTpl("category", array(
		"category"=>$category->getValues(), 
		"products"=>$pagination["data"],
		"pages"=>$pages
	));
});


?>