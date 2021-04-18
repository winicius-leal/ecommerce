<?php

namespace Principal\PagSeguro;
use Exception;
use DOMDocument;
use DOMElement;

class Shipping {

	const PAC = 1;
	const SEDEX = 2;
	const OTHER = 3;

	private $addressRequired;
	private $address; //classe
	private $type;
	private $cost;

	
	public function __construct( bool $addressRequired = true, Address $address, int $type, float $cost){

		if ($type < 1 || $type > 3)
		{

			throw new Exception("Informe um tipo de frete válido");

		}

		$this->addressRequired = $addressRequired;
		$this->address = $address;
		$this->type = $type;
		$this->cost = $cost;
		

	}
	
	public function getDOMElement():DOMElement
	{
	
		$dom = new DOMDocument();

		$shipping = $dom->createElement("shipping");
		$shipping = $dom->appendChild($shipping);

		$addressRequired = $dom->createElement("addressRequired", ($this->addressRequired) ? "true" : "false");
		$addressRequired = $shipping->appendChild($addressRequired);

		if ($addressRequired === true) {
	
		$dom = new DOMDocument();

		$shipping = $dom->createElement("shipping");
		$shipping = $dom->appendChild($shipping);	
			
		$address = $this->address->getDomElement();
		$address = $dom->importNode($address, true);
		$address = $shipping->appendChild($address);

		$type = $dom->createElement("type", $this->type);
		$type = $shipping->appendChild($type);

		$cost = $dom->createElement("cost", number_format($this->cost, 2, ".", ""));
		$cost = $shipping->appendChild($cost);

		}

		return $shipping;

	}




	

}