<?php 

namespace Principal\Model;

use \Principal\Model;
use \Principal\DB\Sql;

class Product extends Model {


	public function listAll(){
		$db = new Sql();
		$dados = $db->select("SELECT * FROM tb_products ORDER BY desproduct");
		return $dados ;
	}

	public static function checkList($list){
		foreach ($list as &$row) {
			$p = new Product();
			$p->setData($row);
			$row = $p->getValues();
		}
		return $list;
	}

	public function save(){

		$sql = new Sql();
		$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :descriproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl)", array(
			":idproduct"=>$this->getidproduct(), //teve q ser adicionado o if ternario no MODEL get
			":desproduct"=>$this->getdesproduct(),
			":descriproduct"=>$this->getdescriproduct(),
			":vlprice"=>$this->getvlprice(),
			":vlwidth"=>$this->getvlwidth(),
			":vlheight"=>$this->getvlheight(),
			":vllength"=>$this->getvllength(),
			":vlweight"=>$this->getvlweight(),
			":desurl"=>$this->getdesurl()
			
		));
		
		$this->setData($results[0]); //coloca no obj novamente os atributos do usuario que acabou de ser persistido no banco atraves da procedure
	}

	public function get($idproduct){//recebe como parametro o id do usuario que eu quero alterar
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", array(
			":idproduct"=>$idproduct
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
		$sql->query("DELETE FROM tb_products WHERE idproduct = :idproduct", array(
			":idproduct"=>$this->getidproduct()
		));

	}

	public function checkPhoto(){
		if(file_exists(
			$_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
			"resoucers" . DIRECTORY_SEPARATOR .
			"site" . DIRECTORY_SEPARATOR .
			"img" . DIRECTORY_SEPARATOR .
			"products" . DIRECTORY_SEPARATOR .
			$this->getidproduct() . ".jpg" )){

			$url = "/resoucers/site/img/products/" . $this->getidproduct() . ".jpg";
		}
		else{

			$url = "/resoucers/site/img/product.jpg";
		}

		return $this->setdesphoto($url);
	}

	public function getValues(){
		$this->checkPhoto();
		$values = parent::getValues();
		return $values;
	}

	public function addPhoto($file){
		
		$extension = explode(".", $file["name"]);
		$extension = end($extension);

		switch ($extension) {

			case 'jpg':
			case 'jpeg':
			$image = imagecreatefromjpeg($file["tmp_name"]);
			break;
			
			case 'gif':
			$image = imagecreatefromgif($file["tmp_name"]);
			break;
			
			case 'png':
			$image = imagecreatefrompng($file["tmp_name"]);
			break;
			
		}

			$dist = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
			"resoucers" . DIRECTORY_SEPARATOR .
			"site" . DIRECTORY_SEPARATOR .
			"img" . DIRECTORY_SEPARATOR .
			"products" . DIRECTORY_SEPARATOR .
			$this->getidproduct() . ".jpg";

		imagejpeg($image, $dist);
		imagedestroy($image);
		$this->checkPhoto();

	}

	public function getFromURL($desurl){//recebe como parametro o id do usuario que eu quero alterar
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_products WHERE desurl = :desurl", array(
			":desurl"=>$desurl
		));

		$this->setData($results[0]);//coloca no obj o resultado da busca feita no select
			
	}
	 public function getCategories(){
	 	$sql = new Sql();
		return $sql->select("
			SELECT * FROM tb_categories a INNER JOIN tb_productscategories b ON a.idcategory = b.idcategory WHERE b.idproduct = :idproduct", array(
			":idproduct"=>$this->getidproduct()
		));

	 }




}

 ?>