<?php

class Encrypt {

	public static function enc($toEncrypt) {
		$largo = strlen($toEncrypt);
		$hashed_key = Encrypt::getHashedKey(enckeycode);
		$block = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
		$pad = $block - (strlen($toEncrypt) % $block);
		$toEncrypt .= str_repeat(chr($pad), $pad);
		
		$cipherText = mcrypt_encrypt(MCRYPT_3DES, $hashed_key, $toEncrypt, MCRYPT_MODE_ECB);
		$encode = base64_encode($cipherText);
		return Encrypt::agregarLargo($encode, $largo);
	}
	
	public static function dec($toDecrypt, $key) {
		$limpio = Encrypt::obtenerLargo($toDecrypt);
		$decode = base64_decode($limpio[1]);
		$decoded = mcrypt_decrypt(MCRYPT_3DES, Encrypt::getHashedKey($key), $decode, MCRYPT_MODE_ECB);
		return Encrypt::limpiar($decoded, $limpio[0]);
	}
	
	public function getHashedKey($key) {
		return substr(hash('sha256', $key, true), 0, 24);
	}
	
	public static function hashContrasena($contrasena) {
		return hash('sha256', $contrasena, true);
	}
	
	private function agregarLargo($encode, $largo) {
		return $largo . "_" . $encode;
	}
	
	private function limpiar($decode, $largo) {
		return substr($decode, 0, $largo);
	}
	
	private function obtenerLargo($toDecode) {
		$limpio = explode("_", $toDecode);
		return $limpio;
	}
}