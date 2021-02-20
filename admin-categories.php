<?php
use \Principal\Model\User;
use \Principal\PageAdmin;
use \Principal\Model\Category;
use \Principal\Model\Product;

$app->get('/admin/categories', function () {
	User::verifyLogin();
	$category = new Category();
	$categories = $category->listAll();
	$page = new PageAdmin();
	$page->setTpl("categories", array("categories"=>$categories));
});

$app->get('/admin/categories/create', function () {
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("categories-create");
});

$app->post('/admin/categories/create', function () {
	User::verifyLogin();
	$category = new Category();
	$category->setData($_POST);
	$category->save();
	echo "<script>document.location='/admin/categories'</script>";
});

$app->get('/admin/categories/:idcategory/delete', function ($idcategory) {
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->delete();
	echo "<script>document.location='/admin/categories'</script>";
});

$app->get('/admin/categories/:idcategory', function ($idcategory) {
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new PageAdmin();
	$page->setTpl("categories-update", array("category"=>$category->getValues()));
});

$app->post('/admin/categories/:idcategory', function ($idcategory) {
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$category->setData($_POST);
	$category->save();
	echo "<script>document.location='/admin/categories'</script>";
	exit;
});






			//ROTA PARA CATEGORIES X PRODUCTS 
//-----------------------------------------------------//


$app->get('/admin/categories/:idcategory/product', function ($idcategory) {
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new PageAdmin();
	$page->setTpl("categories-products", array(
		"category"=>$category->getValues(), 
		"productsRelated"=>$category->getProducts(true),
		"productsNotRelated"=>$category->getProducts(false)
	));
});

$app->get('/admin/categories/:idcategory/product/:idproduct/add', function ($idcategory, $idproduct) {
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$product = new Product();
	$product->get((int)$idproduct);
	$category->addProduct($product);
	echo "<script>document.location='/admin/categories/".$idcategory."/product'</script>";
	exit;
});

$app->get('/admin/categories/:idcategory/product/:idproduct/remove', function ($idcategory, $idproduct) {
	User::verifyLogin();
	$category = new Category();
	$category->get((int)$idcategory);
	$product = new Product();
	$product->get((int)$idproduct);
	$category->removeProduct($product);
	echo "<script>document.location='/admin/categories/".$idcategory."/product'</script>";
	exit;
});

?>