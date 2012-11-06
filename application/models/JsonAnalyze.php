<?php

require_once('system/core/Model.php');

class Json_Analyze extends CI_Model
{
	private $_string;

	public function __construct()
	{
		parent::__construct();
	}

	public function getJsonArray($string)
	{
		$this->_string = strip_tags($string);
		$this->removeCIBenchmarkOutput();
		$json = $this->findJsonString();
		if($json === false) return false;
		$phpArray = json_decode($json,true);
		if($phpArray === false || $phpArray === null) return false;
		return $phpArray;
	}

	private function removeCIBenchmarkOutput()
	{
		$benchmarksStart = strpos($this->_string, 'BENCHMARKS');
		if($benchmarksStart === false) return;
		$this->_string = substr($this->_string, 0, $benchmarksStart);
	}

	private function findJsonString()
	{
		$firstBracket_ = strpos($this->_string, '{');
		$firstSqBracket_ = strpos($this->_string, '[');
		if($firstSqBracket_ === false && $firstBracket_ === false) return false;

		if($firstBracket_ < $firstSqBracket_){
			$firstBracket = $firstBracket_;
			$searchOpenBracket = '{';
			$searchCloseBracket = '}';
			$currentBracket = $firstBracket_;
		}else{
			$firstBracket = $firstSqBracket_;
			$searchOpenBracket = '[';
			$searchCloseBracket = ']';
			$currentBracket = $firstSqBracket_;
		}

		$currentCount = 1;
		$openBracket;
		$closeBracket;

		while($currentCount !== 0){
			$openBracket = strpos($this->_string, $searchOpenBracket, $currentBracket+1);
			$closeBracket = strpos($this->_string, $searchCloseBracket, $currentBracket+1);
			if($closeBracket === false) break;
			if($openBracket > $closeBracket || $openBracket === false){
				$currentCount = $currentCount-1;
				$currentBracket = $closeBracket;
			}else{
				$currentCount++;
				$currentBracket = $openBracket;
			}
		}
		$lastBracket = $closeBracket;
		$json = substr($this->_string, $firstBracket, $lastBracket-$firstBracket+1);
		return $json;
	}
}
