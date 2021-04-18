<?php

namespace Principal\PagSeguro;
use Exception;
use DOMDocument;
use DOMElement;

class Sender {

	private $name;
	private $email;
	private $phone; //classe
	private $cpf;//classe DOCUMENT
	private $hash;

	public function __construct(string $name, string $email, Phone $phone, Document $cpf, string $hash){
		
		if (!$name) {
			throw new Exception("Informe o Nome do Comprador");
			
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new Exception("Informe o Email Valido");
			
		}
		if (!$hash) {
			throw new Exception("Informe a identificação  HASH do comprador");
			
		}

		$this->name = $name;
		$this->email = $email;
		$this->phone = $phone;
		$this->cpf = $cpf;
		$this->hash = $hash;
	}

	public function getDOMElement():DOMElement {
		


		$dom = new DOMDocument();

		$sender = $dom->createElement("sender");//criamos um nó
		
		$dom->appendChild($sender);//adiciona um nó filho em $phone


		$name = $dom->createElement("name", $this->name);
		$sender->appendChild($name);

		$email = $dom->createElement("email", $this->email);
		$sender->appendChild($email);
		
		$phone = $dom->createElement("phone");
		$phone = $this->phone->getDOMElement();
		$phone = $dom->importNode($phone,true);//importa um nó DOM e traz os filhos com o parametro true
		$sender->appendChild($phone);

		$documents = $dom->createElement("documents");
		$sender->appendChild($documents);

		$cpf = $this->cpf->getDOMElement();
		$cpf = $dom->importNode($cpf,true);//importa um nó DOM e traz os filhos com o parametro true
		$documents->appendChild($cpf);//cria um nó filho com o nó importado

		/*
		$hash = $dom->createElement("hash", $this->hash);
		$sender->appendChild($hash);
		*/


		return $sender;

	}

	

}