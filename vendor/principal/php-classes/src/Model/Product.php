<?php 

namespace Principal\Model;

use \Principal\Model;
use \Principal\DB\Sql;

class Product extends Model {


	public static function listAll(){

		$db = new Sql();
		$dados = $db->select("SELECT * FROM tb_products ORDER BY desproduct");
        $fotos = $db->select("SELECT * FROM tb_photos");

        for ($i = 0; $i < count($fotos); $i++){
            $idfotos[] = $fotos[$i]["idproducts"]; //captura os id das fotos
        }
        for($c = 0; $c < count($dados); $c ++){
            $idprodutos[] = $dados[$c]["idproduct"]; //captura os ids dos produtos
        }

        $idfotos = array_unique($idfotos); //elimina valor duplicado do array

        $idfotos = array_values($idfotos);//ordena na ordem crescente

        for($a = 0; $a < count($dados); $a ++){//0 ao 7
            for($b = 0; $b < count($idfotos); $b++){ //0 ao 2
                if ($dados[$a]["idproduct"] == $idfotos[$b]){
                    //echo $dados[$a]["idproduct"]."é igual a ".$idfotos[$b].PHP_EOL;

                    array_push($dados[$a], $dados[$a]["statusphoto"]="Produto possui foto");
                }else{
                    if (!isset($dados[$a]["statusphoto"])){
                        array_push($dados[$a], $dados[$a]["statusphoto"]="Produto não possui foto");
                    }
                }

            }
        }

		return $dados;

	}

    public function listProductsAndPhotos($idproduct){

        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_products WHERE idproduct = :idproduct", array(
            ":idproduct"=>$idproduct
        ));
        $results2 = $sql->select("SELECT * FROM tb_photos WHERE idproducts = :idproducts", array(
            ":idproducts"=>$idproduct
        ));

        foreach ($results2 as $imagem){
            if ($imagem["photomain"] == "1"){
                array_push($results[0], $results[0]["photoprincipal"] = $results2[0]["namephoto"]);
            }elseif ($imagem["photomain"] == "0"){
            array_push($results[0], $results[0]["phototabela"] = $imagem["namephoto"]);
            }
        }

        $this->setData($results[0]);//coloca no obj o resultado da busca
    }

    public static function listAllProductsMainPhotos(){

        $db = new Sql();

        $dados = $db->select("SELECT * FROM tb_products a INNER JOIN tb_photos b WHERE b.idproducts = a.idproduct AND b.photomain = 1");

        return $dados ;
    }

    /* FUNÇÃO SEM NECESSIDADE MOMENTANEO*/
	public static function checkList($list){
        //recebe um ou mais array e seta no obj
		foreach ($list as &$row) {
			$p = new Product();
			$p->setData($row);
			$row = $p->getValues();
		}
		return $list;
	}

	public function save(){

		$sql = new Sql();
		$results = $sql->select("CALL sp_products_save(:idproduct, :desproduct, :vlprice, :vlwidth, :vlheight, :vllength, :vlweight, :desurl, :descriproduct, :sizeproduct, :useproduct, :recommendationproduct, :suggestionproduct )", array(
			":idproduct"=>$this->getidproduct(), //teve q ser adicionado o if ternario no MODEL get
			":desproduct"=>$this->getdesproduct(),
			":vlprice"=>$this->getvlprice(),
			":vlwidth"=>$this->getvlwidth(),
			":vlheight"=>$this->getvlheight(),
			":vllength"=>$this->getvllength(),
			":vlweight"=>$this->getvlweight(),
			":desurl"=>$this->getdesurl(),
            ":descriproduct"=>$this->getdescriproduct(),
            ":sizeproduct"=>$this->getsizeproduct(),
            ":useproduct"=>$this->getuseproduct(),
            ":recommendationproduct"=>$this->getrecommendationproduct(),
            ":suggestionproduct"=>$this->getsuggestionproduct(),
			
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
            $this->getidproduct())){

			$url = "/resoucers/site/img/products/" . $this->getidproduct() . ".jpg";
		}
		else{

			$url = "/resoucers/site/img/product.jpg";
		}

		return $this->setdesphoto($url);
	}

	public function getValues(){
		//$this->checkPhoto();
		$values = parent::getValues();
		return $values;
	}



    public function getFromURL($desurl){//recebe como parametro o id do usuario que eu quero alterar

	    $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_products a INNER JOIN tb_photos b WHERE a.desurl = :desurl AND b.idproducts = a.idproduct", array(
            ":desurl"=>$desurl
        ));

        array_push($results["0"], $results["0"]["photoprincipal"] = $results["1"]["namephoto"]);

        $phototabela[] = $results["0"]["namephoto"];

        unset($results["0"]["namephoto"], $results["0"]["idphotos"], $results["0"]["idproducts"], $results["0"]["photomain"], $results["0"]["0"]);

        array_push($results["0"], $results["0"]["phototabela"] = $phototabela["0"]);

        unset($results["0"]["1"]);

        $this->setData($results["0"]);

    }
    public function getCategories(){
        $sql = new Sql();
        return $sql->select("
			SELECT * FROM tb_categories a INNER JOIN tb_productscategories b ON a.idcategory = b.idcategory WHERE b.idproduct = :idproduct", array(
            ":idproduct"=>$this->getidproduct()
        ));

    }


    /*
        ADD PHOTOS BD
    */

	public function addPhoto($file, $idproduct){
        $photomaintrue = 1;
        $photomainfalse = 0;

        if (!$file["principal"]["type"] == ""){
            $this->removeOldPhotoFromRepository($idproduct, $photomaintrue);
            $this->imageProcessingGD($file["principal"], $idproduct, $photomaintrue);
        }

        if (!$file["tabela"]["type"] == ""){
            $this->removeOldPhotoFromRepository($idproduct, $photomainfalse);
            $this->imageProcessingGD($file["tabela"], $idproduct, $photomainfalse);
        }
	}

    public function removeOldPhotoFromRepository($idproduct, $photomain){

        $sql = new Sql();
        $results = $sql->select("SELECT idphotos FROM `tb_photos` WHERE idproducts = :idproduct AND photomain = :photomain", array("idproduct"=>$idproduct, "photomain"=>$photomain));

        if (empty($results)){
            return;
        }else{
            $directory = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
                "resoucers" . DIRECTORY_SEPARATOR .
                "site" . DIRECTORY_SEPARATOR .
                "img" . DIRECTORY_SEPARATOR .
                "products" . DIRECTORY_SEPARATOR .
                $results[0]["idphotos"] . ".jpg";

            if (file_exists($directory)){
                unlink($directory);
            }
        }

    }

    public function imageProcessingGD($file, $idproduct, $photomain){

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

            case 'webp':
                $image = imagecreatefromwebp($file["tmp_name"]);
                break;

        }

        $lastid = $this->lastIdPhoto();



        $destino = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR .
            "resoucers" . DIRECTORY_SEPARATOR .
            "site" . DIRECTORY_SEPARATOR .
            "img" . DIRECTORY_SEPARATOR .
            "products" . DIRECTORY_SEPARATOR .
            $lastid . ".jpg";

        imagejpeg($image, $destino);
        imagedestroy($image);

        $url = "/resoucers/site/img/products/".$lastid.".jpg";

        $this->savePhotosBD($file, $idproduct, $url, $photomain);

    }

    public function lastIdPhoto(){

        $sql = new Sql();
        $results = $sql->select("SELECT * FROM tb_photos");

        if (!isset($results) or is_null($results)){
            $lastid = 0;
        }else{
            $lastRow = array_pop($results);//ULTIMO ELEMENTO DO ARRAY
            $lastid = $lastRow["idphotos"];
            $lastid ++;
        }

        return $lastid;
    }

    public function savePhotosBD($file, $idproduct, $url, $photomain){

        $sql = new Sql();

        if ($photomain == 1){

            $sql->query("DELETE FROM tb_photos WHERE (idproducts = :idproducts AND photomain = :photomain)", array(
                ":idproducts"=>$idproduct,
                ":photomain"=>$photomain
            ));

            $sql->query("INSERT INTO tb_photos (idproducts, namephoto, photomain) VALUES (:idproducts, :namephoto, :photomain)", array(
                ":idproducts"=>$idproduct,
                ":namephoto"=>$url,
                "photomain"=>$photomain
            ));
        }else{

            $sql->query("DELETE FROM tb_photos WHERE (idproducts = :idproducts AND photomain = :photomain)", array(
                ":idproducts"=>$idproduct,
                ":photomain"=>$photomain
            ));

            $sql->query("INSERT INTO tb_photos (idproducts, namephoto, photomain) VALUES (:idproducts, :namephoto, :photomain)", array(
                ":idproducts"=>$idproduct,
                ":namephoto"=>$url,
                "photomain"=>$photomain
            ));
        }
    }


}

 ?>