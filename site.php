<?php
use \Principal\Page;
use \Principal\Model\Product;
use \Principal\Model\Category;
use \Principal\Model\Cart;
use \Principal\Model\Address;
use \Principal\Model\User;

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


$app->get('/product/:desurl', function ($desurl) {	
	$product = new Product();
	$product->getFromURL($desurl);
	$page = new Page();
	$page->setTpl("product-detail", array("product"=>$product->getValues(),"categories"=>$product->getCategories()));

});

$app->get('/cart', function () {
	$cart = new Cart();
	$cart = $cart->getFromSession();
	$page = new Page();
	$page->setTpl("cart", array(
		"cart"=>$cart->getValues(),
		"products"=>$cart->getProducts(),
		"error"=>Cart::getMsgError()
	));

});

$app->get("/cart/:idproduct/add", function($idproduct){
	
	$product = new Product();

	$product->get((int)$idproduct);//setData no obj os dados do produto

	$cart = Cart::getFromSession();//pega o carrinho da session ou cria um
	
	$qtd = (isset($_GET['qtd'])) ? (int)$_GET['qtd'] : 1; //if ternario, se for 

	for ($i = 0; $i < $qtd; $i++) {
		
		$cart->addProduct($product);//adiciona o produto na tb_cartsproducts

	}

	echo "<script>document.location='/cart'</script>";
	exit;

});

$app->get("/cart/:idproduct/minus", function($idproduct){

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product);

	echo "<script>document.location='/cart'</script>";
	exit;

});

$app->get("/cart/:idproduct/remove", function($idproduct){

	$product = new Product();

	$product->get((int)$idproduct);

	$cart = Cart::getFromSession();

	$cart->removeProduct($product, true);

	echo "<script>document.location='/cart'</script>";
	exit;

});

$app->post("/cart/freight", function(){


	$cart = Cart::getFromSession();

	$cart->setFreight($_POST["zipcode"]);

	echo "<script>document.location='/cart'</script>";
	exit;

});

$app->get("/checkout", function(){

	User::verifyLogin(false);//false pois ñ é rota administrativa

	$cart = Cart::getFromSession();//pega um cart na session ou cria um

	$address = new Address();
	
	$page = new Page();

	$page->setTpl("checkout", array(
		"cart"->$cart->getValues(),
		"address"->$address->getValues()
	));

});

$app->get("/login", function(){
	
	$page = new Page();

	$page->setTpl("login", array(
		"error"=>User::getError(),
		"errorRegister"=>User::getErrorRegister(),
		"registerValues"=>(isset($_SESSION["registerValues"])) ? $_SESSION["registerValues"] : array("name"=>'',"email"=>'',"phone"=>'')
	));

});

$app->post("/login", function(){

	try {

		User::login($_POST["login"], $_POST["password"]);
		
	} catch (Exception $e) {

		User::setError($e->getMessage());
		
	}
	
	echo "<script>document.location='/checkout'</script>";
	exit;

});

$app->get("/logout", function(){
	
	User::logout();
	echo "<script>document.location='/login'</script>";
	exit;

});

$app->post("/register", function(){

	$_SESSION["registerValues"] = $_POST;
	
	if (!isset($_POST["name"]) || $_POST["name"] == '' ) {
		
		User::setErrorRegister("Preencha o seu nome");
		echo "<script>document.location='/login'</script>";
		exit;
	}

	if (!isset($_POST["email"]) || $_POST["email"] == '' ) {
		
		User::setErrorRegister("Preencha o seu email");
		echo "<script>document.location='/login'</script>";
		exit;
	}

	if (!isset($_POST["password"]) || $_POST["password"] == '' ) {
		
		User::setErrorRegister("Preencha o sua senha");
		echo "<script>document.location='/login'</script>";
		exit;
	}

	if (User::checkLoginExist($_POST["email"]) === true) {
		
		User::setErrorRegister("Este e-mail já existe");
		echo "<script>document.location='/login'</script>";
		exit;
	}
	
	$user = new User();

	$user->setData(array(
		"inadmin"=>0,
		"deslogin"=>$_POST["email"],
		"desperson"=>$_POST["name"],
		"desemail"=>$_POST["email"],
		"despassword"=>$_POST["password"],
		"nrphone"=>$_POST["phone"]
	));

	$user->save();

	User::login($_POST["email"],$_POST["password"]);

	echo "<script>document.location='/checkout'</script>";
	exit;

});

?>