<?php

namespace Principal\PagSeguro;
use Exception;
use DOMDocument;
use DOMElement;

class Phone {

	public $areaCode;
	private $number;


	public function __construct(int $areaCode, int $number){

		if (!$areaCode || $areaCode < 11 || $areaCode > 99) {
			
			throw new Exception("Informe o DDD do telefone");
		}

		if (!$number || strlen($number) < 8 || strlen($number) > 9) {

			throw new Exception("Informe o telefone");
		}
		
		$this->areaCode = $areaCode;
		$this->number = $number;

	}

	public function getDOMElement():DOMElement {
		
		$dom = new DOMDocument();

		$phone = $dom->createElement("phone");//criamos um nó
		
		$dom->appendChild($phone);//adiciona um nó filho em $phone

		$areaCode = $dom->createElement("areaCode",$this->areaCode);
		$phone->appendChild($areaCode);//adiciona um nó filho em $type (NAO VAI USAR PQ NAO TEM NO FILHO)

		$number = $dom->createElement("number",$this->number);
		$phone->appendChild($number);//adiciona um nó filho em $type (NAO VAI USAR PQ NAO TEM NO FILHO)

		return $phone;
	}

	

}