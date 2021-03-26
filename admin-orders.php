<?php 
	
use \Principal\PageAdmin;
use \Principal\Model\User;
use \Principal\Model\Order;
use \Principal\Model\OrderStatus;

$app->get("/admin/orders/:idorder/status", function($idorder){

	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$page = new PageAdmin();

	$page->setTpl("order-status", Array(
		"order"=>$order->getValues(),
		"status"=>OrderStatus::listAll(),
		"msgError"=>Order::getError(),
		"msgSuccess"=>Order::getSuccess()
	));
});

$app->post("/admin/orders/:idorder/status", function($idorder){

	User::verifyLogin();

	if (!isset($_POST["idstatus"]) || !(int)$_POST["idstatus"] > 0 ) {
		Order::setError("Informe o status atual");
		echo "<script>document.location='/admin/orders/".$idorder."/status'</script>";
		exit;
	}

	$order = new Order();

	$order->get((int)$idorder);


	$order->setidstatus((int)$_POST["idstatus"]);

	$order->save();

	Order::setSuccess("Status Atualizado");
	
	echo "<script>document.location='/admin/orders/".$idorder."/status'</script>";
	exit;
});

$app->get("/admin/orders/:idorder/delete", function($idorder){

	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$order->delete();

	echo "<script>document.location='/admin/orders'</script>";
	exit;
});

$app->get("/admin/orders/:idorder", function($idorder){

	User::verifyLogin();

	$order = new Order();

	$order->get((int)$idorder);

	$cart = $order->getCart();

	$page = new PageAdmin();

	$page->setTpl("order", Array(
		"order"=>$order->getValues(),
		"cart"=>$cart->getValues(),
		"products"=>$cart->getProducts()
	));
});


$app->get("/admin/orders", function(){

	User::verifyLogin();
	$page = new PageAdmin();

	$page->setTpl("orders", Array(
		"orders"=>Order::listAll()
	));

});



?>