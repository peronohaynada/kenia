<?php
require_once 'constant.definition.php';

class UploaderUtil {
	public static function uploadFile() {
		switch ($_FILES["userfile"]['error']) {
		case UPLOAD_ERR_OK: // No hay error, archivo subido con éxito.
		//primero hay que validar tamaño del archivo!
			if ($_FILES["userfile"]['size'] > 0 && file_size) {
			echo $_FILES["userfile"]['type'];
				/*if ($_FILES["userfile"]['type'] == "application/x-tar") {
					UploaderUtil::untarFile();
				}
				else */if ($_FILES["userfile"]['type'] == "application/x-zip-compressed") {
					UploaderUtil::unzipFile();
				}
				move_uploaded_file($_FILES["userfile"]['tmp_name'], "./".basename($_FILES["userfile"]['name']));
			}
			else {
				// error!
			}
			break;
		case UPLOAD_ERR_INI_SIZE: // El archivo subido excede la directiva upload_max_filesize en php.ini.
			break;
		case UPLOAD_ERR_FORM_SIZE: // El archivo subido excede la directiva MAX_FILE_SIZE que fue especificada en el formulario HTML.
			break;
		case UPLOAD_ERR_PARTIAL: // El archivo subido fue sólo parcialmente cargado.
			break;
		case UPLOAD_ERR_NO_FILE: // Ningún archivo fue subido.
			break;
		case UPLOAD_ERR_NO_TMP_DIR: // Falta la carpeta temporal.
			break;
		case UPLOAD_ERR_CANT_WRITE: // No se pudo escribir el archivo en el disco.
			break;
		case UPLOAD_ERR_EXTENSION: // Una extensión de PHP detuvo la carga de archivos.
			break;
		}
	}
	
	public static function processFile() {
		
	}
	
	public static function untarFile() {
		$tar = new PharData($_FILES["userfile"]['tmp_name']);
		$tar->decompress("tar");
	}
	
	public static function unzipFile() {
		/*$zip = new ZipArchive;
		
		if ($zip->open($_FILES["userfile"]['tmp_name']) === TRUE) {
			$zip->extractTo('./');
			$zip->close();
			
			return true;
		}
		else {
			return false;
		}*/
		$zip = zip_open($_FILES["userfile"]['tmp_name']);
		if ($zip) {
		  while ($zip_entry = zip_read($zip)) {
			$fp = fopen(zip_entry_name($zip_entry), "w");
			if (zip_entry_open($zip, $zip_entry, "r")) {
			  $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
			  fwrite($fp,"$buf");
			  zip_entry_close($zip_entry);
			  fclose($fp);
			}
		  }
		  zip_close($zip);
		}
	}
	
}