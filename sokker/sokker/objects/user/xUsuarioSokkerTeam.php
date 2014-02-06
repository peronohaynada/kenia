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
			Logger::logWarning("Loading ".$usuarioId);
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
		}
		else {
			$this->pSokker = null;
		}
		$lastId = - 1;
		try {
			$db = DBUtil::getConexion();
			$stmt = $db->prepare($query);
			$db->beginTransaction();
			$stmt->bindParam(":sokker_team_id", $sokkerId);
			$stmt->bindParam(":usuario_sokker", Encrypt::enc($this->uSokker));
			$stmt->bindParam(":contrasena_sokker", $this->pSokker);
			$stmt->bindParam(":usuario_id", $usuarioId);
			$stmt->execute();
			$lastId = $db->lastInsertId();
			$db->commit();
		}
		catch (PDOException $e) {
			$db->rollBack();
			$lastId = - 1;
			throw new Exception("Error when inserting in xust line: 52");
		}
		return $lastId;
	}

	private function getId($usuarioId) {
		try {
			$pdo = DBUtil::getConexion();
			$query = "select xust.id from usuario u ";
			$query .= "join x_usuario_sokker_team xust on xust.usuario_id = u.usuario_id ";
			$query .= "where u.usuario_id=:usuario_id";
			// $query .= "and xust.baneado < 3";
			$stmt = $pdo->prepare($query);
			
			$stmt->bindParam(":usuario_id", $usuarioId);
			$stmt->execute();
			$id = $stmt->fetchColumn();
			unset($stmt);
			return $id;
		}
		catch (PDOException $e) {
			throw new Exception("Unable to get id from xust line 72");
		}
	}

	private function loadJuniors() {
		try {
			$this->juniors = array ();
			foreach (Junior::loadJuniors($this->id) as $row) {
				
				Logger::logWarning("Loading ".$row ['id']." name: ".$row ['nombre']." lastname: ".$row ['apellido']);

				$junior = new Junior();
				$junior->setId($row ['id']);
				$junior->setJuniorId($row ['junior_id']);
				$junior->setNombre(Encrypt::dec($row ['nombre']));
				$junior->setApellido(Encrypt::dec($row ['apellido']));
				$junior->setEdad(Encrypt::dec($row ['edad']));
				$junior->setAltura($row ['altura']);
				$junior->setPeso($row ['peso']);
				$junior->setIMC($row ['imc']);
				$junior->setFormacion($row ['formacion']);
				$junior->loadProgreso();
				
				$this->juniors [] = $junior;
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public static function isBanned($usuarioId) {
		try {
			$pdo = DBUtil::getConexion();
			$stmt = $pdo->prepare("update x_usuario_sokker_team set baneado = baneado + 1 where usuario_id=:usuario_id");
			$stmt->bindColumn(":usuario_id", $usuarioId);
			$pdo->beginTransaction();
			$stmt->execute();
			$pdo->commit();
		}
		catch (PDOException $e) {
			$pdo->rollBack();
			throw new Exception("Unable to execute at xust line 111");
		}
	}

	public function setUSokker($uSokker) {
		$this->uSokker = $uSokker;
	}

	public function setPSokker($pSokker) {
		$this->pSokker = $pSokker;
	}
	
	public function getJuniors() {
		return $this->juniors;
	}
}