<?php
use \Principal\Page;
use \Principal\Model\Product;
use \Principal\Model\Category;
use \Principal\Model\Cart;

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
		"products"=>$cart->getProducts()
	));

});

$app->get("/cart/:idproduct/add", function($idproduct){

	$product = new Product();

	$product->get((int)$idproduct);

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

?>