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

		Category::updateFile();//chama a function para atualizar no index a lista de categorias
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

		Category::updateFile(); //chama a function para atualizar no index a lista de categorias


	}

	public static function updateFile(){
		$categories = Category::listAll(); //traz do banco todos os registros de categorias
		$html = []; //array vazio

		foreach ($categories as $row) {
			array_push($html, '<li><a href="/categories/'.$row['idcategory'].'">'.$row['descategory'].'</a></li>');
		}

		// essa funcao :  file_put_contents(filename, data) -> grava dados em um arquivo
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "categories-menu.html", implode('', $html)); //implode faz um array virar uma string


	}

	public function getProducts($related = true){
		$sql = new Sql();

		if ($related === true) {

			return $sql->select("
				SELECT * FROM tb_products WHERE idproduct IN(
					SELECT a.idproduct
					FROM tb_products a
					INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
				);
		", array(":idcategory"=>$this->getidcategory()
	));
		}else{

			return $sql->select("
				SELECT * FROM tb_products WHERE idproduct NOT IN(
					SELECT a.idproduct
					FROM tb_products a
					INNER JOIN tb_productscategories b ON a.idproduct = b.idproduct
					WHERE b.idcategory = :idcategory
				);
		", array(":idcategory"=>$this->getidcategory()
	));

		}
	}


	public function addProduct(Product $product){
		$sql = new Sql();
		$sql->query("INSERT INTO tb_productscategories (idcategory, idproduct) VALUES (:idcategory, :idproduct)", array(":idcategory"=>$this->getidcategory(), ":idproduct"=>$product->getidproduct()));
	}
	public function removeProduct(Product $product){
		$sql = new Sql();
		$sql->query("DELETE FROM tb_productscategories WHERE idcategory = :idcategory AND idproduct = :idproduct", array(":idcategory"=>$this->getidcategory(), ":idproduct"=>$product->getidproduct()));
	}

}

 ?>