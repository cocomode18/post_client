<?php

require_once('application/models/RequestModel.php');
require_once('application/models/JsonAnalyze.php');

class Post extends CI_Controller
{

	private $_requestUrl;
	private $_postKeyVals;
	private $_authUser;
	private $_authPass;
	private $_sendView;
	const	NUM_POST=8;

	public function __construct()
	{
		parent::__construct();
		$this->_requestUrl = '';
		$this->setPostVal();
		$this->setSendView();
	}

	public function index()
	{
		$data['mainContent'] = $this->load->view('post',$this->_sendView,TRUE);
		$this->load->view('base',$data);
	}

	public function sendAction()
	{
		$RequestModel = new Request_Model();
		$requestResult = $RequestModel->analyze($this->_requestUrl,$this->_postKeyVals,$this->_authUser,$this->_authPass);
		if($requestResult){ 
			$this->_sendView['urlError'] = false;
			$this->_sendView['requestContent'] = $requestResult;

			$JsonAnalyze = new Json_Analyze();
			$dumpArray = $JsonAnalyze->getJsonArray($requestResult);
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
		$this->_authUser = $this->input->post('authUser');
		$this->_authPass = $this->input->post('authPass');
	}

	private function setSendView()
	{
		$this->_sendView['inputKeyVal'] = $this->generateInputField();
		$this->_sendView['inputUrl'] = $this->_requestUrl;
		$this->_sendView['authUser'] = $this->_authUser;
		$this->_sendView['authPass'] = $this->_authPass;
	}
}
