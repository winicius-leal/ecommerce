<?php
use \Principal\Model\User;
use \Principal\Model\Product;
use \Principal\PageAdmin;



$app->get('/admin/product', function () {
	User::verifyLogin();
	$product = new Product();
	$products = $product->listAll();
	$page = new PageAdmin();
	$page->setTpl("products", array("products"=>$products));
});

$app->get('/admin/product/create', function () {
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("products-create");
});

$app->post('/admin/product/create', function () {
	User::verifyLogin();
	$product = new Product();
	$product->setData($_POST);
	$product->save();
	echo "<script>document.location='/admin/product'</script>";
});

$app->get('/admin/product/:idproduct', function ($idproduct) {
	User::verifyLogin();
	$product = new Product();
	$product->get((int)$idproduct);

	$page = new PageAdmin();
	$page->setTpl("products-update", array("product"=>$product->getValues()));
});

$app->post('/admin/product/:idproduct', function ($idproduct) {
	User::verifyLogin();
	$product = new Product();
	$product->get((int)$idproduct);
	$product->setData($_POST);
	$product->save();
	$product->addPhoto($_FILES["file"]);
	echo "<script>document.location='/admin/product'</script>";
	exit;
});

$app->get('/admin/product/:idproduct/delete', function ($idproduct) {
	User::verifyLogin();
	$product = new Product();
	$product->get((int)$idproduct);
	$product->delete();
	echo "<script>document.location='/admin/product'</script>";
	exit;
});

?>