<?php
// TABLE x_usuario_sokker_team
// sokker_team_id varchar(256) not null,
// usuario_sokker varchar(256) null,
// usuario_contrasena varchar(256) null,
// usuario_id integer not null,
// foreign key usuario_id references usuario(usuario_id)
require_once 'util/context.class.php';
require_once 'util/db.util.php';
require_once 'util/enc.util.php';
require_once 'util/constant.definition.php';
require_once 'objects/sokker/junior.class.php';

class XUsuarioSokkerTeam {
	private $id;
	private $juniors;
	private $uSokker;
	private $pSokker;
	private $sokkerId;

	public function loadData($usuarioId) {
		try {
			$this->id = $this->getId($usuarioId);
			Logger::logWarning("XUsuarioSokkerTeam->loadData(".$usuarioId.")");
			$this->loadJuniors();
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function guardarCredenciales($sokkerId, $usuarioId, $confirma) {
		$query = "INSERT INTO x_usuario_sokker_team (sokker_team_id, usuario_sokker, contrasena_sokker, usuario_id) VALUES (:sokker_team_id, :usuario_sokker, :contrasena_sokker, :usuario_id)";
		if ($confirma) {
			$this->pSokker = Encrypt::enc($this->pSokker);
			$this->uSokker = Encrypt::enc($this->uSokker);
		}
		else {
			$this->pSokker = null;
			$this->uSokker = null;
		}
		$params = array();
		$lastId = - 1;
		
		$params[":sokker_team_id"] = $sokkerId;
		$params[":usuario_sokker"] = $this->uSokker;
		$params[":contrasena_sokker"] = $this->pSokker;
		$params[":usuario_id"] = $usuarioId;
		
		try {
			$lastId = DBUtil::insert($query, $params);
		}
		catch (PDOException $e) {
			$lastId = - 1;
			throw new Exception("Error when inserting in xust line: 52");
		}
		return $lastId;
	}
	
	public function getCredentials($userId, $confirmacionCredenciales) {
		$query = "SELECT usuario_sokker, contrasena_sokker, id FROM x_usuario_sokker_team WHERE usuario_id=:usuario_id";
		$params = array();
		$params[":usuario_id"] = $userId;
		
		$result = DBUtil::select($query, $params, PDO::FETCH_ASSOC);
		foreach ($result as $row) {
			$this->uSokker = Encrypt::dec($row["usuario_sokker"]);
			$this->pSokker = ($confirmacionCredenciales == 1) ? Encrypt::dec($row["contrasena_sokker"]) : "";
			$this->id = $row["id"];
		}
	}

	public function getId($usuarioId) {
		$query = "select xust.id as ID from usuario u ";
		$query .= "join x_usuario_sokker_team xust on xust.usuario_id = u.usuario_id ";
		$query .= "where u.usuario_id=:usuario_id";
		// $query .= "and xust.baneado < 3";
		
		$params = array();
		$params[":usuario_id"] = $usuarioId;
		try {
			return DBUtil::select($query, $params);;
		}
		catch (PDOException $e) {
			throw new Exception("Unable to get id from xust line 72");
		}
	}

	private function loadJuniors() {
		try {
			$this->juniors = array ();
			foreach (Junior::loadJuniors($this->id) as $row) {
				
				Logger::logWarning("XUsuarioSokkerTeam->loadJuniors() id: ".$row ['id']." name: ".$row ['nombre']." lastname: ".$row ['apellido']);

				$junior = new Junior();
				$junior->setId($row ['id']);
				$junior->setJuniorId(Encrypt::dec($row ['junior_id']));
				$junior->setNombre(Encrypt::dec($row ['nombre']));
				$junior->setApellido(Encrypt::dec($row ['apellido']));
				$junior->setEdad(Encrypt::dec($row ['edad']) - $junior->getJuniorId());
				$junior->setAltura($row ['altura']);
				$junior->setPeso($row ['peso']);
				$junior->setIMC($row ['imc']);
				$junior->setFormacion($row ['formacion']);
				$junior->setWeeksInSchool($row['semanas']);
				$junior->loadProgress();
				
				$this->juniors [] = $junior;
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public static function isBanned($usuarioId) {
		$query = "update x_usuario_sokker_team set baneado = baneado + 1 where usuario_id=:usuario_id";
		$params[":usuario_id"] = $usuarioId;
		try {
			DBUtil::update($query, $params);
		}
		catch (PDOException $e) {
			throw new Exception("Unable to execute at xust line 111");
		}
	}

	public function setUSokker($uSokker) {
		$this->uSokker = $uSokker;
	}
	
	public function getUSokker() {
		return $this->uSokker;
	}

	public function setPSokker($pSokker) {
		$this->pSokker = $pSokker;
	}
	
	public function updateCredentialsSokker($uSokker, $pSokker) {
		$query = "UPDATE x_usuario_sokker_team SET usuario_sokker=:usuario_sokker, contrasena_sokker=:contrasena_sokker where id=:id";
		$params = array();
		$params[':usuario_sokker'] = Encrypt::enc($uSokker);
		$params[':contrasena_sokker'] = Encrypt::enc($pSokker);
		$params[':id'] = $this->id;
		
		DBUtil::update($query, $params);
		$this->uSokker = $uSokker;
		$this->pSokker = $pSokker;
	}
	
	public function getPSokker() {
		return $this->pSokker;
	}
	
	public function getJuniors() {
		return $this->juniors;
	}
}