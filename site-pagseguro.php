<?php
use \Principal\Page;
use \Principal\User;
use \GuzzleHttp\Client;
use \Principal\PagSeguro\Config;

$app->get("/payment/pagseguro", function(){
    
    $client = new Client();
    //foi passado 3 paramentos para a requisição: (metodo POST), (URL com parametos), (Array com verificação de SSL certificado)
    $response = $client->request('POST', Config::getUrlSessions()."?".http_build_query(Config::getAuthentication()), Array("verify"=>false));

    echo $response->getBody()->getContents(); // '{"id": 1420053, "name": "guzzle", ...}'

});
?>