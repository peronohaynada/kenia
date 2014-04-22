<?php
//require_once '../../errors/nco.exception.php';

class NCOHelper {
	private $ncoFileName;
	private $buffer;
	public function NCOHelper($ncoFileName) {
		$this->ncoFileName = $ncoFileName;
		$this->readNCOFile();
	}
	
	private function readNCOFile() {
		$this->buffer = file_get_contents($this->ncoFileName);
	}
	
	public function getBuffer() {
		return $this->buffer;
	}
	
	public function addContentToBuffer($field, $newContent) {
		$this->buffer = str_replace($field, $newContent, $this->buffer);
	}
}