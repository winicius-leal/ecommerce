<?php 

namespace Principal\Model;

use \Principal\Model;
use \Principal\DB\Sql;

class User extends Model {

	const SESSION = "User";
	//const SECRET_IV = "HcodePhp7_Secret_IV";
	const ERROR = "UserError";
	const ERROR_REGISTER = "UserErrorRegister";
	const SUCCESS = "UserSucesss";

	//protected $fields = [
	//	"iduser", "idperson", "deslogin", "despassword", "inadmin", "dtergister"
	//];
	

	public static function login($login, $password):User
	{

		$db = new Sql(); //obj do banco

		$resultadoDaConsulta = $db->select("SELECT * FROM tb_users a INNER JOIN tb_persons b ON a.idperson = b.idperson WHERE a.deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		

		if (count($resultadoDaConsulta) === 0) { //se não encontrar um login
			throw new \Exception("Não foi possível encontrar o login.");
		}

		
		$data = $resultadoDaConsulta[0]; //array da tabela users do banco
		

		if (password_verify($password, $data["despassword"])) {//Verifica se um password corresponde com um hash

			$user = new User();//instancia um objeto da propria classe

			$data['desperson'] = utf8_encode($data['desperson']);
			
			$user->setData($data);//chama um metodo da classe extendida Model passando o array da tabela recuperada

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
		//se o parametro recebido for false é rota de user comum
		//se o parametro recebido for true é rota de user administrador

		if (!User::checkLogin($inadmin)) { //se o retorno for false entra no if
			
			if ($inadmin) {//se $inadmin for = true é rota de de administrador
				echo "<script>document.location='/admin/login'</script>";
			}else {//se $inadmin for = false é rota de usuario comun
				echo "<script>document.location='/login'</script>";
			}
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
			":desperson"=>utf8_decode($this->getdesperson()),
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

		$data = $results[0];

		$data['desperson'] = utf8_encode($data['despassword']);

		$this->setData($data);//coloca no obj o resultado da busca feita no select
	}

	public function update(){

		$sql = new Sql();
		//chama a procedure que faz um UPDATE no banco do novo usuario, faz o inner join e retorna os mesmos dados do usuario alterado
		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
			":iduser"=>$this->getiduser(),
			":desperson"=>utf8_decode($this->getdesperson()),
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




	//function usada no Cart

	public static function getFromSession(){

		$user = new User();
		
		if (isset($_SESSION[User::SESSION])&&(int)$_SESSION[User::SESSION]['iduser']>0) { //se existir a sessao 
			
			$user->setData($_SESSION[User::SESSION]);//seta no obj os valores da session
		}
		return $user; //retorna  o obj 
	}

	public static function checkLogin($inadmin = true){ //por padrão é true, mas pode ser false se for rota de usuario comun
		
		if (
			!isset($_SESSION[User::SESSION]) // se a constante não foi definida
			|| // ou
			!$_SESSION[User::SESSION]// se for falsa ou perdeu valor
			|| //ou
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 //se o iduser>0 é pq existe um id
		){
			//nao esta logado, logo retorna falso
			return false;

		}else{

			if ($inadmin === true && (bool)$_SESSION[User::SESSION]["inadmin"] === true) { //rota de administrador && usuario é administrador

				return true;

			}else if ($inadmin === false) { //rota de usuario comun
				
				return true;

			}else{
				//nao esta logado
				return false;
			}

		}
	}


	public static function setError($msg)
	{

		$_SESSION[User::ERROR] = $msg;

	}

	public static function getError()
	{

		$msg = (isset($_SESSION[User::ERROR]) && $_SESSION[User::ERROR]) ? $_SESSION[User::ERROR] : '';

		User::clearError();

		return $msg;

	}

	public static function clearError()
	{

		$_SESSION[User::ERROR] = NULL;

	}



}

 ?>