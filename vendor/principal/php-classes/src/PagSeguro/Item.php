<?php

namespace Principal\PagSeguro;
use Exception;
use DOMDocument;
use DOMElement;

class Item {

	private $id;
	private $description;
	private $quantity;
	private $amount;

	public function __construct( int $id, string $description, int $quantity, float $amount){

		if (!$id || !$id > 0)
		{
			throw new Exception("Informe o ID do item.");
		}

		if (!$description)
		{
			throw new Exception("Informe a descrição do item.");
		}

		if (!$quantity || !$quantity > 0)
		{
			throw new Exception("Informe a quantidade do item.");
		}

		if (!$amount || !$amount > 0)
		{
			throw new Exception("Informe o valor total do item.");
		}

		$this->id = $id;
		$this->description = $description;
		$this->quantity = $quantity;
		$this->amount = $amount;

	}

	public function getDOMElement():DOMElement
	{
	
		$dom = new DOMDocument();

		$item = $dom->createElement("item");
		$item = $dom->appendChild($item);

		$id = $dom->createElement("id", $this->id);
		$id = $item->appendChild($id);

		$description = $dom->createElement("description", $this->description);
		$description = $item->appendChild($description);

		$quantity = $dom->createElement("quantity", $this->quantity);
		$quantity = $item->appendChild($quantity);		

		$amount = $dom->createElement("amount", number_format($this->amount, 2, ".", ""));
		$amount = $item->appendChild($amount);

		return $item;

	}


	

}