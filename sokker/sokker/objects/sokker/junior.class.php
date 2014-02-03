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
	private $progreso;

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
		return $this->formacion;
	}

	public function getProgreso() {
		return $this->progreso;
	}

	public static function loadJuniors($xustId) {
		try {
			$pdo = DBUtil::getConexion();
			$stmt = $pdo->prepare("SELECT id, junior_id, nombre, apellido, edad, altura, peso, imc, formacion FROM juniors WHERE sokker_team_id=:sokker_team_id");
			$stmt->bindParam(":sokker_team_id", $xustId);
			$stmt->execute();
			
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		catch (PDOException $e) {
			throw new Exception("Unable to load juniors at xust line 96");
		}
	}

	public function loadProgreso() {
		try {
			$h = new Habilidad();
			$h->setJuniorId($this->id);
			
			$this->progreso = array ();
			while ($row = $h->loadSemanas()) {
				$tmpHabilidad = new Habilidad();
				$tmpHabilidad->setHabilidad($row ['habilidad']);
				$tmpHabilidad->setSemanas($row ['semanas']);
				$this->progreso [] = &$tmpHabilidad;
			}
			unset($h);
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}