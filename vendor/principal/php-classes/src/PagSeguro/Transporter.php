<?php

namespace Principal\PagSeguro;

use \GuzzleHttp\Client;
use \Principal\PagSeguro\Config;
use \Principal\Model\Order;
use Exception;
/**
 * 
 */
class Transporter {
	
	public static function createSession(){ //criar uma session com o PAGSEGURO

		$client = new Client();
	    //foi passado 3 paramentos para a requisição: (metodo POST), (URL com parametos), (Array com verificação de SSL certificado)
	    $response = $client->request('POST', Config::getUrlSessions()."?".http_build_query(Config::getAuthentication()), Array("verify"=>false));

	    $xml = simplexml_load_string($response->getBody()->getContents()); // usa uma function PHP chamada simple_load_string para carregar apenas uma string de um XML quo pagseguro responde

	    return((string)$xml->id);



	    //$string = http_build_query(Config::getAuthentication());
	    //$ch = curl_init();
	    //$string = http_build_query(Config::getAuthentication());
	    //curl_setopt($ch, CURLOPT_URL, Config::getUrlSessions()."?".$string);
	    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    //curl_setopt($ch,CURLOPT_POST,true);

	    //$data = curl_exec($ch);

	    //var_dump($data);
	    //return $data;
	    


	}

	public static function sendTransaction(Payment $payment)
	{


		$client = new Client();
		
		$res = $client->request('POST', Config::getUrlTransaction() . "?" . http_build_query
		(Config::getAuthentication()), [
			"verify"=>false,
			"headers"=>[
				"Content-Type"=>"application/xml"
			],
			"body"=>$payment->getDOMDocument()->saveXml()
		]);
		
		$xml = simplexml_load_string($res->getBody()->getContents());

		//var_dump($xml);

		$order = new Order();

		$order->get((int)$xml->reference);

		$order->setPagSeguroTransactionResponse(
			(string)$xml->code,
			(float)$xml->grossAmount,
			(float)$xml->discountAmount,
			(float)$xml->feeAmount,
			(float)$xml->netAmount,
			(float)$xml->extraAmount,
			(string)$xml->paymentLink
		);

		return $xml;
		

	}

	public static function getNotification(string $code, string $type){
		
		$url = "";

		switch ($type) {

	        case 'transaction':
	            $url = Config::getNotificationTransactionURL();
	            break;
	        
	        default:
	            throw new Exception("Notificação Inválida");            
	            break;
	    }

	    $client = new Client();
		
		$res = $client->request('GET', $url . $code . "?" . http_build_query
		(Config::getAuthentication()), [
			"verify"=>false
		]);
		
		$xml = simplexml_load_string($res->getBody()->getContents());

		$order = new Order();

		$order->get((int)$xml->reference);

		if ($order->getidstatus() !== (int)$xml->status) {
			$order->setidstatus((int)$xml->status);
			$order->save();
		}



		$filename = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "resoucers" . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . date('Ymd His') . ".json";



		$file = fopen($filename, "a+");

		fwrite($file, json_encode(['post'=>$_POST, 'xml'=>$xml]));

		fclose($file);

		return $xml;
	}





}

?>