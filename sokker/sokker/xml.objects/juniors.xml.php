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
require_once 'errors/errors.control.php';

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
			Logger::logWarning("Starting to process junior ".$node->name);
			$this->juniorId = Encrypt::enc($node->ID);
			Logger::logWarning($this->juniorId);
			$this->altura = $node->height;
			$this->peso = $node->weight;
			$this->imc = $node->BMI;
			$this->semanas = (string) $node->weeks;
			$this->habilidad = (string) $node->skill;
			$this->sokkerTeamId = $sokkerTeamId;
			$this->edad = Encrypt::enc($node->age);
			
			$this->getIdJunior();
			if (! $this->exists()) {
				Logger::logWarning("Junior does not exist in DB");
				$this->nombre = Encrypt::enc($node->name);
				$this->apellido = Encrypt::enc($node->surname);
				$this->formacion = $node->formation;
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function insertIntoDB() {
		try {
			if ($this->exists()) {
				Logger::logWarning("Updating information");
				$this->updateJunior();
			}
			else {
				Logger::logWarning("Trying to insert Junior");
				$this->insertJunior();
			}
			
			$habilidad = new Habilidad();
			$habilidad->setJuniorId($this->id);
			$habilidad->setHabilidad($this->habilidad);
			$habilidad->setSemanas($this->semanas);
			if (! $this->exists() || $habilidad->hayActualizar()) {
				Logger::logWarning("New week! Inserting new skills");
				$habilidad->insertSemana();
			}
			unset($habilidad);
		}
		catch (PDOException $pdoe) {
			throw new PDOException($pdoe->getMessage(), $pdoe->getCode(), $pdoe->getPrevious());
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function getNodeName() {
		return $this->nodeName;
	}

	private function getIdJunior() {
		$query = "SELECT id FROM juniors WHERE sokker_team_id=:sokker_team_id AND junior_id=:junior_id";
		$params = array ();
		$params [":sokker_team_id"] = $this->sokkerTeamId;
		$params [":junior_id"] = $this->juniorId;
		try {
			$this->id = DBUtil::select($query, $params);
			Logger::logWarning("The junior id is ".$this->id);
		}
		catch (PDOException $e) {
			throw new Exception("Not sure were the junior went! j99");
		}
	}

	private function insertJunior() {
		$insert = "INSERT INTO juniors (junior_id, sokker_team_id, nombre, apellido, edad, altura, peso, imc, formacion, semanas) ";
		$insert .= "VALUES (:junior_id, :sokker_team_id, :nombre, :apellido, :edad, :altura, :peso, :imc, :formacion, :semanas)";
		
		$params = array ();
		$params [":junior_id"] = $this->juniorId;
		$params [":sokker_team_id"] = $this->sokkerTeamId;
		$params [":nombre"] = $this->nombre;
		$params [":apellido"] = $this->apellido;
		$params [":formacion"] = $this->formacion;
		$params [":edad"] = $this->edad;
		$params [":altura"] = $this->altura;
		$params [":peso"] = $this->peso;
		$params [":imc"] = $this->imc;
		$params [":semanas"] = $this->semanas;
		try {
			$this->id = DBUtil::insert($insert, $params);
		}
		catch (PDOException $e) {
			$this->id = - 1;
			throw new Exception("A junior was lost, shame! j131");
		}
	}

	private function updateJunior() {
		try {
			$params [":id"] = $this->id;
			$params [":edad"] = $this->edad;
			$params [":altura"] = $this->altura;
			$params [":peso"] = $this->peso;
			$params [":imc"] = $this->imc;
			$params [":semanas"] = $this->semanas;
			
			$query = "UPDATE juniors SET edad=:edad, altura=:altura, peso=:peso, imc=:imc, semanas=:semanas, sigue_en_escuela=1 WHERE id=:id";
			DBUtil::update($query, $params);
		}
		catch (PDOException $e) {
			throw new Exception("the junior is too tired for new information to be learned! j154");
		}
	}

	public function exists() {
		return (bool) $this->id;
	}
}