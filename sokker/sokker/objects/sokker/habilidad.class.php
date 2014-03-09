<?php

// TABLE habilidad_junior (
// id integer not null references juniors(id) on delete cascade,
// habilidad varchar(256) not null,
// semanas varchar(256) not null
class Habilidad {
	private $juniorId;
	private $habilidad;
	private $semanas;

	public function setJuniorId($juniorId) {
		$this->juniorId = $juniorId;
	}

	public function setHabilidad($habilidad) {
		$this->habilidad = $habilidad;
	}

	public function getHabilidad() {
		return $this->habilidad;
	}

	public function setSemanas($semanas) {
		$this->semanas = $semanas;
	}

	public function getSemanas() {
		return $this->semanas;
	}

	public function insertSemana() {
		$query = 'INSERT INTO habilidad_junior (junior_id, habilidad, semanas) VALUES (:junior_id, :habilidad, :semanas)';
		$params = array();
		$params[':junior_id'] = $this->juniorId;
		$params[':habilidad'] = $this->habilidad;
		$params[':semanas'] = $this->semanas;
		try {
			DBUtil::insert($query, $params);
		}
		catch (PDOException $e) {
			throw new PDOException($e->getMessage(), $e->getCode(), $e->getPrevious());
		}
	}

	private function ultimaSemana() {
		$query = "SELECT semanas FROM habilidad_junior WHERE junior_id=:junior_id ORDER BY semanas ASC LIMIT 1";
		$params = array();
		$params[":junior_id"] = $this->juniorId;
		try {
			return DBUtil::select($query, $params);
		}
		catch (PDOException $e) {
			throw new Exception("Unable to retrieve SEMANAS: habilidad.class line: 62");
		}
	}
	
	public function hayActualizar() {
		$ultima = $this->ultimaSemana();
		if (!isset($ultima) || $ultima == "" || $ultima > $this->getSemanas()) {
			return true;
		}
		return false;
	}

	public function loadWeeks() {
		try {
			$params = array();
			$params[":junior_id"] = $this->juniorId;
			
			$query = "SELECT habilidad, semanas FROM habilidad_junior WHERE junior_id=:junior_id ORDER BY semanas DESC";
			return DBUtil::select($query, $params, PDO::FETCH_ASSOC);
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}