<?php

namespace Principal\PagSeguro;
use Exception;
use DOMDocument;
use DOMElement;

class Address {

	
	private $street;
	private $number;
	private $complement;
	private $district;
	private $city;
	private $state;
	private $country;
	private $postalCode;


	public function __construct(
		string $street, 
		string $number,
		string $complement,
		string $district,
		string $city,
		string $state,
		string $country,
		string $postalCode){

		if (!$street) {
			
			throw new Exception("Informe Logradouro");
		}


		if (!$number) {
			
			throw new Exception("Informe numero");
		}

		if (!$complement) {
			
			throw new Exception("Informe complemento");
		}

		if (!$district) {
			
			throw new Exception("Informe destrito");
		}

		if (!$city) {
			
			throw new Exception("Informe Cidade");
		}

		if (!$state) {
			
			throw new Exception("Informe Estado");
		}

		if (!$country) {
			
			throw new Exception("Informe País");
		}

		if (!$postalCode) {
			
			throw new Exception("Informe Codigo Postal");
		}

		


		$this->street = $street;
		$this->number = $number;
		$this->complement = $complement;
		$this->district = $district;
		$this->city = str_replace("?", "a", $city);
		//$this->city = $city;
		//$this->state = str_replace("?", "a", $state);
		$this->state = $state;
		$this->country = $country;
		$this->postalCode = $postalCode;
		

	}

	public function getDOMElement($node = "address"):DOMElement
	{
	
		$dom = new DOMDocument();

		$address = $dom->createElement($node);
		$address = $dom->appendChild($address); //cria no filho

		$street = $dom->createElement("street", $this->street); //cria elemento
		$street = $address->appendChild($street); //atribui elemento ao nó pai

		$number = $dom->createElement("number", $this->number);
		$number = $address->appendChild($number);
		
		$complement = $dom->createElement("complement", $this->complement);
		$complement = $address->appendChild($complement);

		$district = $dom->createElement("district", $this->district);
		$district = $address->appendChild($district);

		$city = $dom->createElement("city", utf8_encode($this->city));
		$city = $address->appendChild($city);

		$state = $dom->createElement("state", $this->state);
		$state = $address->appendChild($state);

		$country = $dom->createElement("country", $this->country);
		$country = $address->appendChild($country);

		$postalCode = $dom->createElement("postalCode", $this->postalCode);
		$postalCode = $address->appendChild($postalCode);

		return $address;

	}


}