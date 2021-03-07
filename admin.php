<?php
use \Principal\Model\User;
use \Principal\PageAdmin;

$app->get('/admin/', function () {
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("index");
});


$app->get('/admin/login/', function () {
	$page = new PageAdmin($opts=["header"=>false,"footer"=>false]);
	$page->setTpl("login");
});


$app->post('/admin/login/', function () {
	User::login($_POST["login"],$_POST["password"]);
	echo "<script>document.location='/admin/'</script>";
});

$app->get('/admin/logout', function() {

	User::logout();
	echo "<script>document.location='/admin/login'</script>";
	exit;

});


?>