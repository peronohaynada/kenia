<?php
// usuario_id integer not null auto_increment primary key,
// nombre_usuario varchar(64) not null,
// contrasena_usuario varchar(256) not null,
// confirmacion_credenciales_sokker tinyint(1) default 0
require_once 'xUsuarioSokkerTeam.php';
require_once 'util/db.util.php';
require_once 'util/enc.util.php';
require_once 'util/context.class.php';
require_once 'util/VDSokker.php';
require_once 'errors/login.exception.php';
require_once 'errors/register.exception.php';

class Usuario {
	private $usuarioId;
	private $nombreUsuario;
	private $confirmacionCredenciales;
	private $sokkerData;
	private $sokkerId;

	public function registro($usuario, $contrasena, $uSokker, $pSokker, $confirmaCredenciales) {
		$exito = true;
		try {
			$existe = $this->exists($usuario);
			if (! $existe) {
				$sokkerId = VDSokker::loginToSokker($uSokker, $pSokker);
				if ($sokkerId != "") {
					
					// Registro del usuario
					$this->usuarioId = $this->insert($usuario, $contrasena, $confirmaCredenciales);
					// Registro de usuario sokker
					if ($this->usuarioId >= 0) {
						$xUsuarioSokkerTeam = new XUsuarioSokkerTeam();
						$xUsuarioSokkerTeam->setUSokker($uSokker);
						$xUsuarioSokkerTeam->setPSokker($pSokker);
						$xUsuarioSokkerTeamId = $xUsuarioSokkerTeam->guardarCredenciales($sokkerId, $this->usuarioId, $confirmaCredenciales);
						// Descarga de datos de Sokker
						VDSokker::descargaXML($sokkerId, $xUsuarioSokkerTeamId);
						if (! VDSokker::getContextError()) {
							// Carga de datos
							$this->loadSokkerData();
						}
						else {
							throw new RegisterException("Conection failure between sokker and us, please try again later.");
						}
					}
					else {
						throw new RegisterException("Unable to store user in database.");
					}
				}
				else {
					throw new RegisterException(VDSokker::getContextErrorMessage());
				}
			}
			else {
				throw new RegisterException("User Exists.");
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return $exito;
	}

	public function login($usuario, $contrasena) {
		$this->getUserId($usuario, $contrasena);
		if ($this->usuarioId != 0) {
			$this->getSokkerCredentials();
		}
		else {
			throw new LoginException("Username or password invalid.");
		}
	}
	
	public function getSokkerCredentials() {
		$this->sokkerData = new XUsuarioSokkerTeam();
		$sokkerId = $this->sokkerData->getCredentials($this->usuarioId, $this->confirmacionCredenciales);
	}

	public function getUsuarioId() {
		return $this->usuarioId;
	}
	
	public function setUsuarioId($usuarioId) {
		$this->usuarioId = $usuarioId;
	}

	public function getConfirmacionCredenciales() {
		return $this->confirmacionCredenciales;
	}

	public function loadSokkerData() {
		try {
			$this->sokkerData = new XUsuarioSokkerTeam();
			$this->sokkerData->loadData($this->usuarioId);
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	public function updateDataFromSokker() {
		Logger::logWarning("Usuario->updateDataFromSokker() ".$this->sokkerData->getUSokker() . ", " . $this->sokkerData->getPSokker());
		
		$dbId = $this->sokkerData->getId($this->usuarioId);
		Logger::logWarning("{$this->sokkerId} + $dbId");
		VDSokker::descargaXML($this->sokkerId, $dbId);
	}
	
	public function loginToSokker() {
		$this->sokkerId = VDSokker::loginToSokker($this->sokkerData->getUSokker(), $this->sokkerData->getPSokker());
	}

	private function exists($usuario) {
		$query = "SELECT COUNT(1) FROM usuario WHERE nombre_usuario=:nombre_usuario";
		$params = array ();
		$params [":nombre_usuario"] = $usuario;
		try {
			return DBUtil::select($query, $params) == 1;
		}
		catch (PDOException $e) {
			throw new Exception("Not sure if user is already registered! u102");
		}
	}

	private function insert($usuario, $contrasena, $confirmaCredenciales) {
		$params = array ();
		$lastId;
		
		$params [":nombre_usuario"] = $usuario;
		$params [":contrasena_usuario"] = Encrypt::hashContrasena($contrasena);
		$params [":confirmacion_credenciales_sokker"] = $confirmaCredenciales;
		$query = "INSERT INTO usuario (nombre_usuario, contrasena_usuario, confirmacion_credenciales_sokker) VALUES (:nombre_usuario, :contrasena_usuario, :confirmacion_credenciales_sokker)";
		
		try {
			$lastId = DBUtil::insert($query, $params);
		}
		catch (PDOException $e) {
			$lastId = - 1;
			throw new Exception("unexpected error when saving: u124");
		}
		return $lastId;
	}

	public function getSokkerData() {
		return $this->sokkerData;
	}

	private function getUserId($usuario, $contrasena) {
		$params = array ();
		
		$params [":nombre_usuario"] = $usuario;
		$params [":contrasena_usuario"] = Encrypt::hashContrasena($contrasena);
		
		$query = "SELECT usuario_id, confirmacion_credenciales_sokker FROM usuario WHERE nombre_usuario=:nombre_usuario AND contrasena_usuario=:contrasena_usuario";
		$result = DBUtil::select($query, $params, PDO::FETCH_ASSOC);
		
		foreach ($result as $row) {
			$this->usuarioId = $row ["usuario_id"];
			$this->confirmacionCredenciales = (bool) $row ["confirmacion_credenciales_sokker"];
		}
		/*
		 * if (!isset($this->usuarioId)) { throw new Exception("Password incorrect."); }
		 */
	}
}