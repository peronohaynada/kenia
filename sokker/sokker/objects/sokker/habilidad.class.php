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
		try {
			echo $this->juniorId." ".$this->habilidad." ".$this->semanas."<br>";
			$pdo = DBUtil::getConexion();
			$stmt = $pdo->prepare("INSERT INTO habilidad_junior (junior_id, habilidad, semanas) VALUES (:junior_id, :habilidad, :semanas)");
			$stmt->bindParam(":junior_id", $this->juniorId);
			$stmt->bindParam(":habilidad", $this->habilidad);
			$stmt->bindParam(":semanas", $this->semanas);
			
			$pdo->beginTransaction();
			$stmt->execute();
			$pdo->commit();
		}
		catch (PDOException $e) {
			$pdo->rollBack();
			throw new Exception("Unable to insert Semana: habilidad.class line: 47");
		}
	}

	private function ultimaSemana() {
		try {
			$pdo = DBUtil::getConexion();
			$stmt = $pdo->prepare("SELECT semanas FROM habilidad_junior WHERE junior_id=:junior_id ORDER BY semanas ASC LIMIT 1");
			
			$stmt->bindParam(":junior_id", $this->juniorId);
			$stmt->execute();
			$semanas = $stmt->fetchColumn();
			unset($stmt);
			
			return $semanas;
		}
		catch (PDOException $e) {
			throw new Exception("Unable to retrieve SEMANAS: habilidad.class line: 62");
		}
	}
	
	public function hayActualizar() {
		$ultima = $this->ultimaSemana();
		if (!((bool) $ultima) || $ultima < $this->getSemanas()) {
			return true;
		}
		return false;
	}

	public function loadSemanas() {
		try {
			$pdo = DBUtil::getConexion();
			$stmt = $pdo->prepare("SELECT habilidad, semanas FROM habilidad_junior WHERE junior_id=:junior_id");
			
			$stmt->bindParam(":junior_id", $this->juniorId);
			$stmt->execute();
			
			$this->progreso = array ();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch (PDOException $e) {
			throw new Exception("Unable to retrieve HABILIDAD and SEMANAS: habilidad.class line 78");
		}
	}
}