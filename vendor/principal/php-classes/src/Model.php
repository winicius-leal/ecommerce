<?php 

namespace Principal;

class Model {

	private $values = []; //armazena os dados de todos os atributos do nosso objeto

	public function setData($dadosDoUsuario) //recebe o array da tabela user do banco de dados
	{

		foreach ($dadosDoUsuario as $key => $value)
		{

			$this->{"set".$key}($value); //setiduser(1)... faz os metodos set dinamicamente

		}

	}

	public function __call($name, $args) // é chamado automaticamente quando um metodo magico (get)(set) for executado na classe filho "User" ou outras...
	{


		$method = substr($name, 0, 3);//3 primeiros caracteres (set)ou(get)
		$fieldName = substr($name, 3, strlen($name)); // restante dos caracteres (iduser)...


		//if (in_array($fieldName, $this->fields))
		//{
			
			switch ($method)
			{

				case "get": //if ternario: se values[$fieldName] foi definida retorna ela, se nao, retorna null pra quem chamar o metodo get
					return (isset($this->values[$fieldName])) ? $this->values[$fieldName] : NULL; //retorna o valor do array "values"
				break;

				case "set":
					$this->values[$fieldName] = $args[0]; //atribui o valor no array "values"
				break;

			}

		//}

	}

	public function getValues()
	{

		return $this->values;

	}

}

 ?>
