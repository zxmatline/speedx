<?php
class getiniParms{
	private $ini = "../init/webconfig.json";
	function _construct($path){
		$this->ini = $path;
	}
	public function getini(){
		if(!is_file($this->iniPath)){return "err";}
		$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($this->ini)), true);
		return $CONFIG;	
	}
}
?>