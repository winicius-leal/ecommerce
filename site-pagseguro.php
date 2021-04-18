<?php
use \Principal\Page;
use \Principal\Model\User;
use \Principal\Model\Order;
use \Principal\PagSeguro\Config;
use \Principal\PagSeguro\Transporter;
use \Principal\PagSeguro\Document;
use \Principal\PagSeguro\Phone;
use \Principal\PagSeguro\Address;
use \Principal\PagSeguro\Sender;
use \Principal\PagSeguro\CreditCard\Holder;
use \Principal\PagSeguro\Shipping;
use \Principal\PagSeguro\CreditCard\Installment;
use \Principal\PagSeguro\CreditCard;
use \Principal\PagSeguro\Item;
use \Principal\PagSeguro\Payment;
use \Principal\Model\Cart;

$app->post("/payment/notification", function(){

    Transporter::getNotification($_POST["notificationCode"], $_POST["notificationType"]);

});

$app->get("/payment/success", function(){
    
    User::verifyLogin(false);

    $order = new Order();

    $order->getFromSession();

    $page = new Page();

    $page->setTpl("payment-success", Array(
        "order"=>$order->getValues(),
        "msgError"=>Order::getError()
        )
    );

});


$app->get("/payment/success/boleto", function(){
    
    User::verifyLogin(false);

    $order = new Order();

    $order->getFromSession();

    $order->get((int)$order->getidorder());

    $page = new Page();

    $page->setTpl("payment-success-boleto", Array(
        "order"=>$order->getValues(),
        "msgError"=>Order::getError()
        )
    );

});


$app->post("/payment/credit", function(){
    
    User::verifyLogin(false);

    $order = new Order();

    $order->getFromSession();

    $order->get((int) $order->getidorder());

    $address = $order->getAddress();

    $cart = $order->getCart();

    $cpf = new Document(Document::CPF, $_POST['cpf']);

    $phone = new Phone($_POST["ddd"],$_POST["phone"]);

    $shippingAddress = new Address(
        $address->getdesaddress(),
        $address->getdesnumber(),
        $address->getdescomplement(),
        $address->getdesdistrict(),
        $address->getdescity(),
        $address->getdesstate(),
        $address->getdescountry(),
        $address->getdeszipcode()
    ); 

    //Dados do comprador.
    $sender = new Sender($order->getdesperson(), $order->getdesemail(), $phone, $cpf, $_POST["hash"]);

    $birthDate = new DateTime($_POST["birth"]);

    //Dados do dono do cartão de crédito.
    $holder = new Holder($_POST["name"], $cpf, $birthDate, $phone);

    //frete
    $shipping = new Shipping($addressRequired = false, $shippingAddress, Shipping::PAC, (float)$cart->getvlfreight());

    //Dados do parcelamento.
    $installment = new Installment((int)$_POST["installments_qtd"], (float)$_POST["installments_value"]);

    //Dados do endereço de cobrança.
    $billingAddress = new Address(
        $address->getdesaddress(),
        $address->getdesnumber(),
        $address->getdescomplement(),
        $address->getdesdistrict(),
        $address->getdescity(),
        $address->getdesstate(),
        $address->getdescountry(),
        $address->getdeszipcode()
    );

    $creditCard = new CreditCard($_POST["token"], $installment, $holder, $billingAddress);

    $payment = new Payment($order->getidorder(), $sender, $shipping);

    foreach ($cart->getProducts2() as $product) {
        
        $item = new Item(
            (int)$product['idproduct'],
            $product['desproduct'],
            (int)$product['nrqtd'],           
            (float)$product['vlprice'] + (float)$product['vlfreight'] / (int)$product['nrqtd']
           
        );

        $payment->addItem($item);
    }

    $payment->setCreditCard($creditCard);

    //$dom = $payment->getDOMDocument();
    Transporter::sendTransaction($payment);
    echo json_encode(['success'=>true]); //imprimi pra finalizar e saber que deu tudo certo
    

    //$dom = new DOMDocument();

    //$test = $creditCard->getDOMElement();
    //$testNode = $dom->importNode($test, true);
    //$dom->appendChild($testNode);

    //echo $dom->saveXML();

    //var_dump($order->getValues());
    //var_dump($address->getValues());
    //var_dump($cart->getValues());

});

$app->post("/payment/boleto", function(){
    
    User::verifyLogin(false);

    $order = new Order();

    $order->getFromSession();

    $order->get((int) $order->getidorder());

    $address = $order->getAddress();

    $cart = $order->getCart();

    $cpf = new Document(Document::CPF, $_POST['cpf']);

    $phone = new Phone($_POST["ddd"],$_POST["phone"]);

    $shippingAddress = new Address(
        $address->getdesaddress(),
        $address->getdesnumber(),
        $address->getdescomplement(),
        $address->getdesdistrict(),
        $address->getdescity(),
        $address->getdesstate(),
        $address->getdescountry(),
        $address->getdeszipcode()
    ); 

    //Dados do comprador.
    $sender = new Sender($order->getdesperson(), $order->getdesemail(), $phone, $cpf, $_POST["hash"]);

    $birthDate = new DateTime($_POST["birth"]);

    //frete
    $shipping = new Shipping($addressRequired = false, $shippingAddress, Shipping::PAC, (float)$cart->getvlfreight());

    $payment = new Payment($order->getidorder(), $sender, $shipping);

    foreach ($cart->getProducts2() as $product) {
        
        $item = new Item(
            (int)$product['idproduct'],
            $product['desproduct'],
            (int)$product['nrqtd'],           
            (float)$product['vlprice'] + (float)$product['vlfreight'] / (int)$product['nrqtd']
           
        );

        $payment->addItem($item);
    }

    $payment->setBoleto();

    //$dom = $payment->getDOMDocument();
    Transporter::sendTransaction($payment);
    echo json_encode(['success'=>true]); //imprimi pra finalizar e saber que deu tudo certo
    

    //$dom = new DOMDocument();

    //$test = $creditCard->getDOMElement();
    //$testNode = $dom->importNode($test, true);
    //$dom->appendChild($testNode);

    //echo $dom->saveXML();

    //var_dump($order->getValues());
    //var_dump($address->getValues());
    //var_dump($cart->getValues());

});

$app->get("/payment", function(){
    
    User::verifyLogin(false);

    $order = new Order();

    $order->getFromSession();

    $years = Array();

    for($y = date("Y"); $y < date("Y") + 14; $y++ ){
        array_push($years, $y);
    }

    $page = new Page();

    $page->setTpl("payment", Array(
        "order"=>$order->getValues(),
        "msgError"=>Order::getError(),
        "years"=>$years,
        "pagseguro"=>Array(
            "urlJS"=>Config::getUrlJS(),
            "id"=>Transporter::createSession(),
            "maxInstallmentNoInterest"=>Config::MAX_INSTALLMENT_NO_INTEREST,
            "maxInstallment"=>Config::MAX_INSTALLMENT
        )
    ));

});



?>