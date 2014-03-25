<?php

// TABLE juniors (
// junior_id varchar(256) not null primary key,
// sokker_team_id varchar(256) not null,
// nombre varchar(256) not null,
// apellido varchar(256) not null,
// edad varchar(256) not null,
// altura varchar(256) not null,
// peso varchar(256) not null,
// imc varchar(256) not null,
// formacion tinyint(1) not null default 1,
// semanas varchar(256) not null,
// sigue_en_escuela tinyint(1) not null default 1,
// foreign key sokker_team_id references x_usuario_sokker_team(sokker_team_id) on delete cascade
require_once 'habilidad.class.php';
require_once 'util/talento.util.php';

class Junior {
	private $id;
	private $juniorId;
	private $nombre;
	private $apellido;
	private $edad;
	private $altura;
	private $peso;
	private $imc;
	private $formacion;
	private $progress;
	private $totalWeeks;

	public function setId($id) {
		$this->id = $id;
	}

	public function getId() {
		return $this->id;
	}

	public function setJuniorId($juniorId) {
		$this->juniorId = $juniorId;
	}

	public function getJuniorId() {
		return $this->juniorId;
	}

	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}

	public function getNombre() {
		return $this->nombre;
	}

	public function setApellido($apellido) {
		$this->apellido = $apellido;
	}

	public function getApellido() {
		return $this->apellido;
	}

	public function setEdad($edad) {
		$this->edad = $edad;
	}

	public function getEdad() {
		return $this->edad;
	}

	public function setAltura($altura) {
		$this->altura = $altura;
	}

	public function getAltura() {
		return $this->altura;
	}

	public function setPeso($peso) {
		$this->peso = $peso;
	}

	public function getPeso() {
		return $this->peso;
	}

	public function setIMC($imc) {
		$this->imc = $imc;
	}

	public function getIMC() {
		return $this->imc;
	}

	public function setFormacion($formacion) {
		$this->formacion = $formacion;
	}

	public function getFormacion() {
		return ($this->formacion == 1) ? "PL" : "GK";
	}

	public function getProgress() {
		return $this->progress;
	}
	
	public function getTotalWeeks() {
		return $this->totalWeeks;
	}

	public static function loadJuniors($xustId) {
		$query = "SELECT id, junior_id, nombre, apellido, edad, altura, peso, imc, formacion FROM juniors WHERE sokker_team_id=:sokker_team_id ORDER BY semanas ASC";
		
		$params = array();
		$params[":sokker_team_id"] = $xustId;
		try {
			return DBUtil::select($query, $params, PDO::FETCH_ASSOC);
		}
		catch (PDOException $e) {
			throw new Exception("Unable to load juniors at xust line 96");
		}
	}

	public function loadProgress() {
		try {
			$h = new Habilidad();
			$h->setJuniorId($this->id);
			
			$this->progress = array ();
			foreach ($h->loadWeeks() as $row) {
				$tmpHabilidad = new Habilidad();
				$tmpHabilidad->setHabilidad($row ['habilidad']);
				$tmpHabilidad->setSemanas($row ['semanas']);
				$this->progress [] = $tmpHabilidad;
			}
			unset($h);
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	public function getTalent() {
		$weeks = array();
		foreach ($this->progress as $progress) {
			$weeks[] = intval($progress->getHabilidad());
		}
		$this->totalWeeks = count($weeks);
		if ($this->totalWeeks == 0) {
			Logger::logWarning("Junior->getTalent() empty data for id: ".$this->id);
		}
		return TalentUtil::juniorTalent($weeks);
	}
	
	public function __toString() {
		//$junior =  "<div class='div-table-col'>{$this->getJuniorId()}</div>";
		$junior = "<div class='div-table-col div-name'>{$this->getNombre()}</div>";
		$junior .= "<div class='div-table-col div-lastname'>{$this->getApellido()}</div>";
		$junior .= "<div class='div-table-col div-age'>{$this->getEdad()}</div>";
		$junior .= "<div class='div-table-col div-height'>{$this->getAltura()}</div>";
		$junior .= "<div class='div-table-col div-weight'>{$this->getPeso()}</div>";
		$junior .= "<div class='div-table-col div-imc'>{$this->getIMC()}</div>";
		$junior .= "<div class='div-table-col div-formation'>{$this->getFormacion()}</div>";
		$junior .= "<div class='div-table-col div-talent'>{$this->getTalent()}</div>";
		$junior .= "<div class='div-table-col div-talent'>{$this->getTotalWeeks()}</div>";
		
		return $junior;
	}
}