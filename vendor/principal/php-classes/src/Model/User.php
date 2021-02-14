<?php 

namespace Principal\Model;

use \Principal\Model;
use \Principal\DB\Sql;

class User extends Model {

	const SESSION = "User";

	//protected $fields = [
	//	"iduser", "idperson", "deslogin", "despassword", "inadmin", "dtergister"
	//];

	public static function login($login, $password):User
	{

		$db = new Sql(); //obj do banco

		$resultadoDaConsulta = $db->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		

		if (count($resultadoDaConsulta) === 0) { //se não encontrar um login
			throw new \Exception("Não foi possível encontrar o login.");
		}

		
		$dadosDoUsuario = $resultadoDaConsulta[0]; //array da tabela users do banco
		

		if (password_verify($password, $dadosDoUsuario["despassword"])) {//Verifica se um password corresponde com um hash

			$user = new User();//instancia um objeto da propria classe
			
			$user->setData($dadosDoUsuario);//chama um metodo da classe extendida Model passando o array da tabela recuperada

			$_SESSION[User::SESSION] = $user->getValues();

			
			return $user; //retorna o obj do usuario encontrado

		} else {//senha invalida

			throw new \Exception("Não foi possível fazer login.");

		}

	}

	public static function logout()
	{

		$_SESSION[User::SESSION] = NULL;

	}

	public static function verifyLogin($inadmin = true)
	{

		

		if (
			!isset($_SESSION[User::SESSION]) // se a constante não foi definida
			|| // ou
			!$_SESSION[User::SESSION]// se for falsa ou perdeu valor
			|| //ou
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 //se o iduser>0 é pq existe um id 
			||//ou
			(bool)$_SESSION[User::SESSION]["iduser"] !== $inadmin //verifica se esse usuario tem permissao de administrador 
			
		) {
			
			header("Location: /admin/login");
			exit;

		}

	}

	public function listAll(){
		$db = new Sql();
		$dados = $db->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY (b.desperson)");
		return $dados ;
	}

	public function save(){

		$sql = new Sql();
		//chama a procedure que persisti no banco o novo usuario, faz o inner join e retorna os mesmos dados do usuario novo
		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
		));

		$this->setData($results[0]); //coloca no obj novamente os atributos do usuario que acabou de ser persistido no banco atraves da procedure
	}

	public function get($iduser){//recebe como parametro o id do usuario que eu quero alterar
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", array(
			":iduser"=>$iduser
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
		$sql->query("CALL sp_users_delete(:iduser)", array(
			":iduser"=>$this->getiduser()
		));


	}

}

 ?>