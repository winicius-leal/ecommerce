<?php 

namespace Principal\Model;

use \Principal\Model;
use \Principal\DB\Sql;

class Category extends Model {


	public function listAll(){
		$db = new Sql();
		$dados = $db->select("SELECT * FROM tb_categories ORDER BY descategory");
		return $dados ;
	}

	public function save(){

		$sql = new Sql();
		$results = $sql->select("CALL sp_categories_save(:idcategory, :descategory)", array(
			":idcategory"=>$this->getidcategory(), //teve q ser adicionado o if ternario no MODEL get
			":descategory"=>$this->getdescategory()
		));

		$this->setData($results[0]); //coloca no obj novamente os atributos do usuario que acabou de ser persistido no banco atraves da procedure
	}

	public function get($idcategory){//recebe como parametro o id do usuario que eu quero alterar
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_categories WHERE idcategory = :idcategory", array(
			":idcategory"=>$idcategory
		));

		$this->setData($results[0]);//coloca no obj o resultado da busca feita no select
	}

	public function update(){

		$sql = new Sql();
		//chama a procedure que faz um UPDATE no banco do novo usuario, faz o inner join e retorna os mesmos dados do usuario alterado
		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
			":iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]); //coloca no obj novamente os atributos do usuario que acabou de ser persistido no banco atraves da procedure

	}
	public function delete(){

		$sql = new Sql();
		//chama a procedure que faz um DELETE no banco do usuario
		$sql->query("DELETE FROM tb_categories WHERE idcategory = :idcategory", array(
			":idcategory"=>$this->getidcategory()
		));


	}

}

 ?>