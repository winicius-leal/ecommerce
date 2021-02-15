<?php

session_start();
require ("vendor/autoload.php");
use Rain\Tpl;
use Principal\Page;
use Principal\PageAdmin;
use Principal\Model\User;
use Principal\Model\Category;


$app = new \Slim\Slim();

$app->get('/', function () {
	$page = new Page();
	$page->setTpl("index");
});



			//ROTA PARA ADMIN
//-----------------------------------------------------//

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
	//header("Location: /admin/");
	echo "<script>document.location='/admin/'</script>";

});






			//ROTA PARA USUARIO
//-----------------------------------------------------//

//ROTA PARA LISTAR NA TELA TODOS OS USUARIOS
$app->get('/admin/users/', function () {
	User::verifyLogin();
	$users = User::listAll();
	$page = new PageAdmin();
	$page->setTpl("users", array("users"=>$users));
});

//ROTA PARA CADASTRAR UM USUARIO
$app->get('/admin/users/create/', function () {
	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("users-create");
});
//ROTA POST ---------------------------------------------
$app->post('/admin/users/create', function () {
	User::verifyLogin();

	$user = new User();//criando um novo obj para guardar os dados no banco
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0; //se foi definido recebe valor de 1 se nao 0
	$user->setData($_POST);//faz o DAO do array $_POST nos atributos do obj 
	$user->save();//pega os dados do novo usuario e chama a function para salvar no banco
	echo "<script>document.location='/admin/users'</script>";
	exit;
});
//ROTA PARA DELETAR UM USUARIO
$app->get('/admin/users/:iduser/delete', function ($iduser) {
	User::verifyLogin();//verifica se existe uma sessao ativa
	$user = new User();//cria um ainstancia nova do obj
	$user->get((int)$iduser);//chama o metodo passando o parametro id do usuario recebido da URL
	$user->delete();
	echo "<script>document.location='/admin/users'</script>";
	exit;
	
});
//ROTA PATA EDITAR O USUARIO
$app->get('/admin/users/:iduser', function ($iduser) {
	User::verifyLogin();//verifica se existe uma sessao ativa
	$user = new User();//cria um ainstancia nova do obj
	$user->get((int)$iduser); //chama o metodo passando o parametro id do usuario recebido da URL
	$page = new PageAdmin();
	$page->setTpl("users-update", array("user"=>$user->getValues()));//passando o array "user" com os dados do usuario que estou editando
});
//ROTA POST ---------------------------------------------
$app->post('/admin/users/:iduser', function ($iduser) {
	User::verifyLogin();//verifica se existe uma sessao ativa
	$user = new User();//cria um ainstancia nova do obj
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0; //se foi definido recebe valor de 1 se nao 0
	$user->get((int)$iduser);//chama o metodo passando o parametro id do usuario recebido da URL
	$user->setData($_POST);
	$user->update();
	echo "<script>document.location='/admin/users'</script>";
	exit; 
	
});







			//ROTA PARA CATEGORIES
//-----------------------------------------------------//

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

$app->run();



?>