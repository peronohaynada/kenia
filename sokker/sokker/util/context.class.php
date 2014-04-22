<?php
/**
 * Context class
 */
include_once 'xml.objects/xmlFileNames.class.php';
include_once 'constant.definition.php';
require_once 'enc.util.php';

class Context {
	private $options;
	private $data;
	
	private $cookie;
	private $expires;
	private $headers;
	private $body;
	
	private $error;
	private $errorMessage;
	
	public function Context() {
		$this->error = false;
		$this->data = array();
	}
	
	public function setData($username, $password) {
		$this->data['ilogin'] = $username;
		$this->data['ipassword'] = $password;
	}

	private function setLoginOption() {
		// use key 'http' even if you send the request to https://...
		$this->options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($this->data)
			),
		);
	}
	
	private function setOption() {
		unset($this->options['http']['content']);
		$this->options['http']['header'] = "Cookie:" . $this->headers['Set-Cookie'] . "\r\n" . $this->headers['Expires'];
		$this->options['http']['method'] = "GET";
	}
	
	public function sendLoginRequestToSokker() {
		try {
			$this->setLoginOption();
			$headers = $this->sendRequest(loginUrl);
			$sokkerID = "";
			
			if (strpos($this->body, "OK teamID") !== false) {
				$this->headers = array();
				foreach($headers['wrapper_data'] as $line) {
					if(strpos($line, 'HTTP') === 0) {
						$headers[0] = $line;
					}
					else {
						list($key, $value) = explode(': ', $line);
						$this->headers[$key] = $value;
					}
				}
				
				$sokkerID = str_replace("OK teamID=", "", $this->body);
				$sokkerID = Encrypt::enc($sokkerID, enckeycode);
			}
			else {
				$this->errorMessage = $this->body;
				$this->error = true;
			}
			return $sokkerID;
		}
		catch (Exception $e) {
			throw new LoginException("the login must be in riot! cc74");
		}
	}
	
	public function downloadXML($xmlName) {
		try {
		if ($this->error === false) {
			$this->setOption();
			$headers = $this->sendRequest(xmlUrl.$xmlName);
		}
		else {
			echo "unable to download data<br>";
		}
		
		// in case of error the body will display the authentication error from sokker.org
		return $this->body;
		}
		catch (Exception $e) {
			Logger::logWarning($e->getMessage());
			throw new Exception($e->getMessage());
		}
	}
	
	private function sendRequest($url) {
		try {
		$fp = fopen($url, 'r', null, stream_context_create($this->options));
		$headers = stream_get_meta_data($fp);
		$this->body = stream_get_contents($fp);
		fclose($fp);
		return $headers;
		}
		catch (Exception $e) {
			throw new Exception("the server is sleeping, come back later! cc95");
		}
	}
	
	public function isError() {
		return $this->error;
	}
	
	public function getErrorMessage() {
		return $this->errorMessage;
	}
	
	public static function getSokkerId() {
		return self::$sokkerID;
	}
}