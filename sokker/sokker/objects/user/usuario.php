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

class Usuario {
	private $usuarioId;
	private $nombreUsuario;
	private $confirmacionCredenciales;
	private $sokkerData;

	public function registro($usuario, $contrasena, $uSokker, $pSokker, $confirmaCredenciales) {
		$exito = true;
		try {
			$existe = $this->exists($usuario);
			if (! $existe) {
				$vds = new VDSokker();
				$sokkerId = $vds->validarCredenciales($uSokker, $pSokker);
				if ($sokkerId != "") {
					
					// Registro del usuario
					$this->usuarioId = $this->insert($usuario, $contrasena, $confirmaCredenciales);
					// Registro de usuario sokker
					if ($this->usuarioId >= 0) {
						$xUsuarioSokkerTeam = new XUsuarioSokkerTeam();
						$xUsuarioSokkerTeam->setUSokker($uSokker);
						$xUsuarioSokkerTeam->setPSokker($pSokker);
						$xustId = $xUsuarioSokkerTeam->guardarCredenciales($sokkerId, $this->usuarioId, $confirmaCredenciales);
						// Descarga de datos de Sokker
						$vds->descargaXML($sokkerId, $xustId);
						/*if (! $vds->getContextError()) {
							// Carga de datos
							$this->loadSokkerData();
						}
						else {
							$exito = false;
						}*/
					}
					else {
						$exito = false;
					}
				}
			}
			else {
				$exito = false;
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		return $exito;
	}

	public function logueo($usuario, $contrasena) {
		try {
			$pdo = DBUtil::getConexion();
			$stmt = $pdo->prepare("SELECT usuario_id FROM usuario WHERE nombre_usuario=:nombre_usuario AND contrasena_usuario=:contrasena_usuario");
			
			$stmt->bindParam(":nombre_usuario", $usuario);
			$stmt->bindParam(":contrasena_usuario", Encrypt::hashContrasena($contrasena));
			$stmt->execute();
			
			$this->usuarioId = $stmt->fetchColumn();
			
			return (bool) $this->usuarioId;
		}
		catch (PDOException $e) {
			throw new Exception("problems on log in: usuario line 69");
		}
	}

	public function getUsuarioId() {
		return $this->usuarioId;
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

	private function exists($usuario) {
		try {
			$pdo = DBUtil::getConexion();
			$stmt = $pdo->prepare("SELECT COUNT(1) FROM usuario WHERE nombre_usuario=:nombre_usuario");
			
			$stmt->bindParam(":nombre_usuario", $usuario);
			$stmt->execute();
			
			return $stmt->fetchColumn() == 1;
		}
		catch (PDOException $e) {
			throw new Exception("Not sure if user is already registered! u102");
		}
	}

	private function insert($usuario, $contrasena, $confirmaCredenciales) {
		$pdo = DBUtil::getConexion();
		$lastId;
		
		try {
			$stmt = $pdo->prepare("INSERT INTO usuario (nombre_usuario, contrasena_usuario, confirmacion_credenciales_sokker) VALUES (:nombre_usuario, :contrasena_usuario, :confirmacion_credenciales_sokker)");
			
			$stmt->bindParam(":nombre_usuario", $usuario);
			$stmt->bindParam(":contrasena_usuario", Encrypt::hashContrasena($contrasena));
			$stmt->bindParam(":confirmacion_credenciales_sokker", $confirmaCredenciales);
			$pdo->beginTransaction();
			$stmt->execute();
			$lastId = $pdo->lastInsertId();
			$pdo->commit();
		}
		catch (PDOException $e) {
			$pdo->rollback();
			$lastId = - 1;
			throw new Exception("unexpected error when saving: u124");
		}
		return $lastId;
	}

	public function getSokkerData() {
		return $this->sokkerData;
	}
}