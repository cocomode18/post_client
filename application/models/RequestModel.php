<?php

require_once('system/core/Model.php');

class Request_Model extends CI_Model
{
	private $_requestUrl;
	private $_postKeyVals;

	public function __construct($requestUrl,$postKeyVals)
	{
		parent::__construct();
		$this->_requestUrl = $requestUrl;
		$this->_postKeyVals = $postKeyVals;
	}

	public function analyze()
	{
		$postData = $this->generatePostData();
		$result = $this->sendPostRequest($postData);
		return $result;
	}

	private function generatePostData()
	{
		$result = array();
		for($i=0;$i<count($this->_postKeyVals['keys']);$i++)
		{
			if($this->_postKeyVals['keys'][$i] !== '' && $this->_postKeyVals['vals'][$i] !== ''){
				$result[$this->_postKeyVals['keys'][$i]]
						= $this->_postKeyVals['vals'][$i];
			}
		}
		return ($result)? :false;
	}

	private function sendPostRequest($data)
	{
		$data = http_build_query($data);
		$url = parse_url($this->_requestUrl);

		if ($url['scheme'] != 'http') { 
			return false;
		}

		$host = $url['host'];
		$path = $url['path'];
		$fp = fsockopen($host, 80, $errno, $errstr, 30);

		if ($fp){
			fputs($fp, "POST $path HTTP/1.1\r\n");
			fputs($fp, "Host: $host\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, "Content-length: ". strlen($data) ."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $data);

			$result = ''; 
			while(!feof($fp)) {
				$result .= fgets($fp, 128);
			}
		} else { 
			return array(
					'status' => 'err', 
					'error' => "$errstr ($errno)"
				);
		}

		fclose($fp);
		$result = explode("\r\n\r\n", $result, 2);

		$header = isset($result[0]) ? $result[0] : '';
		$content = isset($result[1]) ? $result[1] : '';

		return array(
				'status' => 'ok',
				'header' => $header,
				'content' => $content
			);
	}
}
