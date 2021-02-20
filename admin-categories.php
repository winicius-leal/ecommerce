<?php
use \Principal\Model\User;
use \Principal\PageAdmin;
use \Principal\Model\Category;

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
});







			//ROTA PARA CATEGORIES - SITE
//-----------------------------------------------------//


$app->get('/categories/:idcategory', function ($idcategory) {
	$category = new Category();
	$category->get((int)$idcategory);
	$page = new Page();
	$page->setTpl("category", array("category"=>$category->getValues(), "products"=>[]));
});

?>