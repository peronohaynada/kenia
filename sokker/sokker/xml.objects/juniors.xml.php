<?php

// TABLE juniors (
// id integer not null auto_increment primary key,
// junior_id varchar(256) not null,
// sokker_team_id integer not null references x_usuario_sokker_team(id) on delete cascade,
// fecha_descarga timestamp not null,
// nombre varchar(256) not null,
// apellido varchar(256) not null,
// edad varchar(256) not null,
// altura varchar(256) not null,
// peso varchar(256) not null,
// imc varchar(256) not null,
// formacion tinyint(1) not null default 1,
// semanas varchar(256) not null,
// sigue_en_escuela tinyint(1) not null default 1
require_once 'xml.interface.php';
require_once 'util/enc.util.php';
require_once 'util/constant.definition.php';
require_once 'objects/user/xUsuarioSokkerTeam.php';
require_once 'objects/sokker/habilidad.class.php';
require_once 'util/context.class.php';
require_once 'util/date.util.php';

class JuniorXmlImpl implements XmlI {
	private $id;
	private $juniorId;
	private $sokkerTeamId;
	private $nombre;
	private $apellido;
	private $edad;
	private $altura;
	private $peso;
	private $imc;
	private $formacion;
	private $semanas;
	private $habilidad;
	private $exists;
	private $nodeName = "junior";

	public function loadFromXML(SimpleXMLElement $node, $sokkerTeamId) {
		try {
			$this->juniorId = Encrypt::enc($node->ID);
			$this->altura = $node->height;
			$this->peso = $node->weight;
			$this->imc = $node->BMI;
			$this->semanas = $node->weeks;
			$this->habilidad = $node->skill;
			$this->sokkerTeamId = $sokkerTeamId;
			
			$this->getIdJunior();
			if (! $this->isExists()) {
				$this->nombre = Encrypt::enc($node->name);
				$this->apellido = Encrypt::enc($node->surname);
				$this->edad = Encrypt::enc($node->age);
				$this->formacion = $node->formation;
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function insertIntoDB() {
		try {
			if ($this->isExists()) {
				$this->updateJunior();
			}
			else {
				$this->insertJunior();
			}
			
			$habilidad = new Habilidad();
			$habilidad->setJuniorId($this->id);
			$habilidad->setHabilidad($this->habilidad);
			$habilidad->setSemanas($this->semanas);
			if (! $this->isExists() || $habilidad->hayActualizar()) {
				$habilidad->insertSemana();
			}
			unset($habilidad);
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function getNodeName() {
		return $this->nodeName;
	}

	private function getIdJunior() {
		try {
			$pdo = DBUtil::getConexion();
			$stmt = $pdo->prepare("SELECT id FROM juniors WHERE sokker_team_id=:sokker_team_id AND junior_id=:junior_id");
			
			$stmt->bindParam(":sokker_team_id", $this->sokkerTeamId);
			$stmt->bindParam(":junior_id", $this->juniorId);
			$stmt->execute();
			
			$this->id = $stmt->fetchColumn();
			unset($stmt);
		}
		catch (PDOException $e) {
			throw new Exception("Not sure were the junior went! j99");
		}
	}

	private function insertJunior() {
		try {
			$pdo = DBUtil::getConexion();
			
			$insert = "INSERT INTO juniors (junior_id, sokker_team_id, nombre, apellido, edad, altura, peso, imc, formacion, semanas) ";
			$insert .= "VALUES (:junior_id, :sokker_team_id, :nombre, :apellido, :edad, :altura, :peso, :imc, :formacion, :semanas)";
			
			$stmt = $pdo->prepare($insert);
			$stmt->bindParam(":junior_id", $this->juniorId);
			$stmt->bindParam(":sokker_team_id", $this->sokkerTeamId);
			$stmt->bindParam(":nombre", $this->nombre);
			$stmt->bindParam(":apellido", $this->apellido);
			$stmt->bindParam(":formacion", $this->formacion);
			$stmt->bindParam(":edad", $this->edad);
			$stmt->bindParam(":altura", $this->altura);
			$stmt->bindParam(":peso", $this->peso);
			$stmt->bindParam(":imc", $this->imc);
			$stmt->bindParam(":semanas", $this->semanas);
			
			$pdo->beginTransaction();
			$stmt->execute();
			$this->id = $pdo->lastInsertId();
			$pdo->commit();
		}
		catch (PDOException $e) {
			$pdo->rollBack();
			$this->id = - 1;
			throw new Exception("A junior was lost, shame! j131");
		}
	}

	private function updateJunior() {
		try {
			$pdo = DBUtil::getConexion();
			$stmt;
			$stmt = $pdo->prepare("UPDATE juniors SET edad=:edad, altura=:altura, peso=:peso, imc=:imc, semanas=:semanas WHERE id=:id");
			$stmt->bindParam(":id", $this->id);
			$stmt->bindParam(":edad", $this->edad);
			$stmt->bindParam(":altura", $this->altura);
			$stmt->bindParam(":peso", $this->peso);
			$stmt->bindParam(":imc", $this->imc);
			$stmt->bindParam(":semanas", $this->semanas);
			
			$pdo->beginTransaction();
			$stmt->execute();
			$pdo->commit();
		}
		catch (PDOException $e) {
			$pdo->rollBack();
			throw new Exception("the junior is too tired for new information to be learned! j154");
		}
	}

	public function isExists() {
		return (bool) $this->id;
	}
}