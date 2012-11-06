<?php

require_once('application/models/RequestModel.php');
require_once('application/models/JsonAnalyze.php');

class Post extends CI_Controller
{

	private $_requestUrl;
	private $_postKeyVals;
	private $_sendView;
	const	NUM_POST=6;

	public function __construct()
	{
		parent::__construct();
		$this->_requestUrl = '';
		$this->setPostVal();
	}

	public function index()
	{
		$data['mainContent'] = $this->load->view('post',$this->_sendView,TRUE);
		$this->load->view('base',$data);
	}

	public function sendAction()
	{
		$RequestModel = new Request_Model($this->_requestUrl,$this->_postKeyVals);
		$requestResult = $RequestModel->analyze();
		if($requestResult['status'] === 'ok'){ 
			$this->_sendView['urlError'] = false;
			$this->_sendView['requestContent'] = $requestResult['content'];

			$JsonAnalyze = new Json_Analyze();
			$dumpArray = $JsonAnalyze->getJsonArray($requestResult['content']);
			if($dumpArray !== false){ var_dump($dumpArray); }
		}else{
			$this->_sendView['urlError'] = true;
			$this->_sendView['requestContent'] = '';
		}
		$this->index();
	}

	private function generateInputField()
	{
		$result = '';
		for($i=0;$i<self::NUM_POST;$i++){
				$result .= '<input type="text" id="key'.$i.'" name="key'.$i.'" placeholder="key" value="'.$this->_postKeyVals['keys'][$i].'">';
				$result .= '<input type="text" id="val'.$i.'" name="val'.$i.'" placeholder="val" value="'.$this->_postKeyVals['vals'][$i].'">';
		}
		return $result;
	}

	private function setPostVal()
	{
		$this->_requestUrl = ($this->input->post('request_url'))? :'';
		for($i=0;$i<self::NUM_POST;$i++){
			$this->_postKeyVals['keys'][$i] = ($this->input->post("key$i"))? :'';
			$this->_postKeyVals['vals'][$i] = ($this->input->post("val$i"))? :'';
		}
		$this->_sendView['inputKeyVal'] = $this->generateInputField();
		$this->_sendView['inputUrl'] = $this->_requestUrl;
	}
}
