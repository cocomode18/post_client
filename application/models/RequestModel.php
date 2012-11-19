<?php

require_once('system/core/Model.php');

class Request_Model extends CI_Model
{
	private $_requestUrl;
	private $_postKeyVals;
	private $_basicAuth;

	public function __construct()
	{
		parent::__construct();
	}

	public function analyze($requestUrl,$postKeyVals,$authUser=false,$authPass=false)
	{
		if(!$requestUrl) return false;
		$this->_requestUrl = $requestUrl;
		$this->_postKeyVals = $postKeyVals;
		$this->_basicAuth = array('user'=>$authUser,'pass'=>$authPass);
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
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';
		if($data) $headers[] = 'Content-Length: '. strlen(http_build_query($data));
		if($this->_basicAuth['user'] && $this->_basicAuth['pass'])
			$headers[] = 'Authorization: Basic '.base64_encode($this->_basicAuth['user'].':'.$this->_basicAuth['pass']);

		$options = array(
				'http' => array(
						'method' => 'POST',
						'header' => implode("\r\n", $headers),
					)
				);
		$contents = file_get_contents($this->_requestUrl, false, stream_context_create($options));
		return $contents;
	}
}
