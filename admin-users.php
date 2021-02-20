<?php
use \Principal\Model\User;
use \Principal\PageAdmin;

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

?>