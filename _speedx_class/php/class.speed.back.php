<?php
class Speed {
	private $plugings = array();//模块类列表
	private $vars= array();//模块变量列表
	private $js = array();//包含的外部JS文件引用
	private $css = array();//包含的外部css文件引用
	private $php = array();
	private $jsCode = array();//包含要插入的javascript语句块
	private $phpCode = array();//包含要插入php语句块
	private $PROJECTROOT = "";//网站的项目目录，项目开发时用的
	private $DOCUMENTROOT = "";//网站根目录,默认是document_root;
	private $TPLSDIR = "";
	private $OUTCACHEDIR = "";
	private $CONFIG = array();
	
	
	public function __construct($root = "",$config = ""){
		if(empty($config)){
			$config = rtrim($_SERVER['DOCUMENT_ROOT'],"/")."/"."config.json";			
		}else{
			$config = rtrim($_SERVER['DOCUMENT_ROOT'],"/")."/".ltrim($config,"/");
		}
		//获取主配置文件中的项目目录，依此设置以下值：PROJECTROOT：项目根目录的相对主目录（/www.xiaomingzx.com/）； DOCUMENTROOT：项目根目录（如：d:\wamp\www\ www.xiaomingzx.com\）；
		$this->CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents($config)), true);	
		if($root == ""){
			$this->PROJECTROOT = rtrim("/".ltrim($this->CONFIG['ProjectRoot'],"/"),"/")."/";
		}else{
			$this->PROJECTROOT =  rtrim("/".ltrim($root,"/"),"/")."/";
		};	
		$this->DOCUMENTROOT = rtrim($_SERVER['DOCUMENT_ROOT'],"/")."/".ltrim($this->PROJECTROOT,"/");
		$this->TPLSDIR = $this->DOCUMENTROOT.ltrim($this->CONFIG['tplsdir'],"/");
		$this->OUTCACHEDIR = $this->DOCUMENTROOT . ltrim($this->CONFIG['outcachedir'],"/");
		
		$includeHead = '<meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">'."\n".
		'<meta name="viewport" content="width=device-width, initial-scale=1">'."\n".
		'<link rel="stylesheet" href="'.$this->PROJECTROOT."speed/thirdPlugins/bootstrap/css/bootstrap.min.css".'">'."\n".
		'<link rel="stylesheet" href="'.$this->PROJECTROOT."speed/thirdPlugins/bootstrap/css/bootstrap-theme.min.css".'">'."\n".
		'<script src="'.$this->PROJECTROOT.'speed/sysplugins/jquery/jquery-1.11.1.min.js"></script>'."\n".
		'<script src="'.$this->PROJECTROOT.'speed/sysplugins/jquery/base64.js"></script>'."\n".
		'<script src="'.$this->PROJECTROOT.'speed/thirdPlugins/bootstrap/js/bootstrap.min.js"></script>'."\n".
		'<script src="'.$this->PROJECTROOT.'speed/class.Speed.js"></script>';
		array_push($this->js,$includeHead);		
	}
	
	public function __get($varName){
		$projectDir = rtrim(ltrim($this->CONFIG['tplPluginsRoot'],"/"),"/")."/".$varName."/";
		$curPluginDir = $this->DOCUMENTROOT . $projectDir;
		$includeFile = $curPluginDir."t.".$varName.".php";
		
		$this->CONFIG['tplPluginsRoot'] =rtrim( ltrim($this->CONFIG['tplPluginsRoot'],"/"),"/")."/";
			
		$curPluginJS = $this->PROJECTROOT . $this->CONFIG['tplPluginsRoot'] . $varName."/"."t.".$varName.".js";
		$curJSAbsolute = $curPluginDir."t.".$varName.".js";	

		$curPluginCSS = $this->PROJECTROOT . $this->CONFIG['tplPluginsRoot'] . $varName."/"."t.".$varName.".css";	
		$curCSSAbsolute = $curPluginDir."t.".$varName.".css";	
		
		if(!isset($this->plugings[$varName])){			
			if(is_file($includeFile) && !in_array($includeFile,$this->php)){			
				include_once($includeFile);
				$tPHP = 'include_once("' .$includeFile. '");';
				array_push($this->php,$tPHP);	
				$this->plugings[$varName] = new $varName;	
			}else{
				echo "plugins:{".$varName."} is not defined!";
				exit();
			};
				
			
			if(is_file($curJSAbsolute) ){
				$includeJS = '<script language="javascript" type="text/javascript" src="'.$curPluginJS.'"></script>';
				if(!in_array($includeJS,$this->js)){
					array_push($this->js,$includeJS);
				};
			}			
			
			if(is_file($curCSSAbsolute)){
				$includeCSS ='<link href="'.$curPluginCSS.'"  rel="stylesheet" type="text/css" />';
				if(!in_array($includeCSS,$this->css)){
					array_push($this->css,$includeCSS);
				};
			}			
		}
		
		$this->plugings[$varName]->rootDir =  $curPluginDir;
		$this->plugings[$varName]->dataFile = $curPluginDir."t.".$varName.".data.php";
		$this->plugings[$varName]->PROJECTROOT = $this->PROJECTROOT;
		$this->includeFiles($this->plugings[$varName]->includeFiles());	

		return $this->plugings[$varName];
	}
		
	/*
	*****变量内容赋值
	*/
	public function assign($key, $value){
		$this->vars[$key]=$value;
	}
	
	
	/*
	****向模块中包含指定的包含文件
	*/
	private function includeFiles($value){
		foreach($value as $key => $value){
			switch($key){
			case "JSF":	
				$this->add_js($value);
				break;
			case "CSSF":
				$this->add_css($value);
				break;
			case "PHPF":
				$this->add_php($value);
				break;
			case "JSCODE":
				$this->add_jsCode($value);
				break;
			case "PHPCODE":
				$this->add_phpCode($value);
				break;
			}
		}
	}
		
	/*
	****添加包含php引用文件,内部方法;
	*/	
	private function add_php($value){
/*包含的JS文件目录书写标准为：相对于当前项目的根目录，即$this->PROJECTROOT目录；如：/speed/sysplugins/jquery/base64.js*/
		if($value == ""){return;}
		if(is_array($value)){
			if( !empty($value)){
				foreach($value as $val){
					$curPHP = $this->DOCUMENTROOT.ltrim($val,"/");
					$val = 'include_once("' .$curPHP. '");';
					if(!in_array($val,$this->php)){			
						array_push($this->php,$val);
					}
				}
			}
		}else{
			$curPHP = $this->DOCUMENTROOT.ltrim($value,"/");
			$value = 'include_once("' .$curPHP. '");';
			if(!in_array($value,$this->php)){			
					array_push($this->php,$value);
			}
		}
	}
		
	/*
	****添加JS引用文件,内部方法;
	*/	
	private function add_js($value){
/*包含的JS文件目录书写标准为：相对于当前项目的根目录，即$this->PROJECTROOT目录；如：/speed/sysplugins/jquery/base64.js*/
		if($value == ""){return;}
		if(is_array($value) ){
			if(!empty($value)){
				foreach($value as $val ){
					$val = trim($val);
					if($val !== "" || !empty($val)){
						$curJS = $this->PROJECTROOT.ltrim($val,"/");
						$val = '<script language="javascript" type="text/javascript" src="'.$curJS.'"></script>';
						if(!in_array($val,$this->js)){			
							array_push($this->js,$val);
						}
					}
				}
			}
		}else{
			$value = trim($value,"/");
			if($value !== ""){
				$value = ltrim($value,"/");
				$curJS = $this->PROJECTROOT . $value;
				$value = '<script language="javascript" type="text/javascript" src="'.$curJS.'"></script>';
				if(!in_array($value,$this->js)){			
					array_push($this->js,$value);
				}
			}
		}	
	}

	
	/*
	****添加css引用文件,内部方法;
	*/	
	private function add_css($value){
	/*包含的CSS文件目录书写标准为：相对于当前项目的根目录，即$this->PROJECTROOT目录；如：/speed/sysplugins/css/buttons.css*/
		if($value == ""){return;}
		if(is_array($value)){
			if(!empty($value)){
				foreach($value as $val){
					$curCSS = $this->PROJECTROOT.ltrim($val,"/");
					$val = '<link href="'.$curCSS.'"  rel="stylesheet" type="text/css" />';
					if(!in_array($val,$this->css)){			
						array_push($this->css,$val);
					}
				}
			}
		}else{
			$curCSS = $this->PROJECTROOT.ltrim($value,"/");
			$value = '<link href='.$curCSS.'  rel="stylesheet" type="text/css" />';
			if(!in_array($value,$this->css)){			
				array_push($this->css,$value);
			}
		}		
	}
	
	
	/*
	****添加javascript引用语句块,由功能模块的run方法返回，内部方法;
	*/	
	private function add_jsCode($value){
		if($value == ""){return;}
		if(is_array($value)){
			if(!empty($value)){
				foreach($value as $val){
					if(!in_array($val,$this->jsCode)){			
						array_push($this->jsCode,$val);
					}
				}
			}
		}else{
			if(!in_array($value,$this->jsCode)){			
					array_push($this->jsCode,$value);
			}
		}		
	}
	
	
	private function add_phpCode($value){
		if($value == ""){return;}
		if(is_array($value) ){
			if(!empty($value)){
				foreach($value as $val){
					if(!in_array($val,$this->phpCode)){			
						array_push($this->phpCode,$val);
					}
				}
			}
		}else{
			if(!in_array($value,$this->phpCode)){
				array_push($this->phpCode,$value);
			}
		}		
	}
	
	
	/*
	****向主调用模块文件中输出JS引用文件,一定要在尾部调用，只需调用一次，可自动加载模块中所需要的附加JS外部文件;
	*/	
	private function includeJS(){
		$outjs = "";
		if(!empty($this->js)){
			foreach($this->js as $value){
				$outjs .= $value."\n";
			}
		}
		return $outjs;
	}



	/*
	****向主调用模块文件中输出CSS引用文件,一定要在尾部调用，只需调用一次，可自动加载模块中所需要的附加CSS外部文件;
	*/	
	private function includeCSS(){
		$outcss = "";
		if(!empty($this->css)){
			foreach($this->css as $value){
				$outcss .= $value."\n";
			}
		}
		return $outcss;
	}
	
	/*
	****向主调用模块文件中输出php包含文件,一定要在尾部调用，只需调用一次，可自动加载模块中所需要的附加php外部文件;
	*/	
	private function includePHP(){
		$outphp = "";
		if(!empty($this->php)){
			foreach($this->php as $value){
				$outphp .= $value."\n";
			}
		}
		return $outphp;
	}
	
	
	/*
	****向主调用模块文件中的<script></script>内输出动态js运行语块，此js运行语句块由功能模块内的includeRun方法返回,一定要在尾部调用，只需调用一次;
	*/	
	private function insertJS(){
		$outjquery = "";
		if(!empty($this->jsCode)){
			foreach($this->jsCode as $value){
				$outjquery .= $value."\n";
			}
		}
		return $outjquery;
	}
	
	private function insertPHP(){
		$outPHPCode = "";
		if(!empty($this->phpCode)){
			foreach($this->phpCode as $value){
				$outPHPCode .= $value."\n";
			}
		}
		return $outPHPCode;
	}
	
	
	/*
	****变量清空
	*/
	public function assign_reset(){$this->vars = array();}
	public function jsReset(){$this->js = array();}
	public function cssReset(){$this->css = array();}
	public function Reset(){$this->run= array();}
	public function jsCodereset(){$this->jsCode= array();}
	public function phpCode(){$this->phpCode= array();}

	
	/*
	****替换指定内容内的模块变量，内部方法
	*/
	public function replaceWithAssign($text){
		$keys = array_keys($this->vars);
		$vals = array_values($this->vars);
		
		foreach($keys as $key=>$value){
			$keys[$key] = '<{$'.$value.'}>';
		}
		return str_replace($keys,$vals,$text);
	}
	
	/*
	****读取模块文件内的指定区域内容,如不指定区域名称，则读取整个模块文件内容。
	*/
	public function selectWithAssign($tplfile,$sectionName="", $type = "section"){
		//type = php; js; css;section		
		$phpPreg = "/\<\?php\s+\/\/name\s*=\s*". $sectionName ."([\s\S]+?)\?\>/";
		$jsPreg = "/\<script\s+name\s*=\s*". $sectionName ."\>([\s\S]+?)\<\/script\>/";
		$cssPreg = "/\<style\s+name\s*=\s*". $sectionName .">([\s\S]+?)\<\/style\>/";
		$sectionPreg ="/\[section\s+name\s*=\s*". $sectionName ."\]([\s\S]+?)\[\/section\]/";
		
		$preg = "";
		$content = "";
		switch($type){
		case "php":
			$preg = $phpPreg;
			break;
		case "css":
			$preg = $cssPreg;
			break;
		case "js":
			$preg = $jsPreg;
			break;
		case "section":
		default:
			$preg = $sectionPreg;
		}
		if(is_file($tplfile)){			
			$content = file_get_contents($tplfile);
			if($sectionName == ""){
				return $content;
			}			
		}else{
			$content = $tplfile;
		}
		preg_match($preg,$content,$matches);
		if(isset($matches[1])){
			$matches[1] = preg_replace("/\/\*[\s\S]+?\*\//", "",$matches[1]);
			$matches[1] = preg_replace("/\<\!--[\s\S]+?--\>/", "",$matches[1]);
			return $matches[1];
		}else{
			return "";
		}
	}
	
	
	/*
	****替换模块文件内指定区域的模块变量,返回替换后的内容
	*/	
	public function replaceFileWithAssign($tplfile,$sectionName="",$type = "section"){
		$sec = $this->selectWithAssign($tplfile,$sectionName,$type);
		return $this->replaceWithAssign($sec);
	}	
	
	
	/*
	****测试模块
	*/	
	public function testTPL($testPlugins = ""){
		$this->__get($testPlugins);
		$this->assign("title","模块：".$testPlugins."测试");
		$this->assign("body",$this->plugings[$testPlugins]->show());
		$parms = array(
		  "tplFile" => "SpeedStandardTpl.php",
		  "outPutFile" => "defaultOutputFile.php",
		);
		$this->show($parms);
	}
	
	/*
	****替换模块文件内的模块变量并输出至最终成品网页文件;
	*/	
	public function show($options = array()){
		$default = array(
		  "tplFile" => "",
		  "outPutFile" => "defaultOutputFile.php",
		  "lShow"=>true,//是否显示内容
		  "lReWrite"=>true,//是否改写已经存在的缓存文件
		  "sectionName"=>"",//模块的区域名称
		  "includeJS" => "includeJS",//模块内$ncludeJs变量
		  "includeCSS" => "includeCSS",//模块内$includeCSS变量
		  "includePHP" => "includePHP",//模块内$includePHP变量
		  "insertJS" => "insertJS",//模块内$insertJS变量
		  "insertPHP" => "insertPHP"//模块内$insertPHP变量
		);				
		$opt = array_merge($default,$options);
		$opt['tplFile'] = $this->TPLSDIR.$opt['tplFile'];
		$opt['outPutFile']  = $this->OUTCACHEDIR.$opt['outPutFile'] ;
		
		if(!is_file($opt['tplFile'])){echo "no tpl !"; return;}
		
		$includeJS = empty($this->includeJS()) ? "":$this->includeJS();
		$includeCSS = empty($this->includeCSS()) ? "" : $this->includeCSS();
		$includePHP = empty($this->includePHP()) ? "" : $this->includePHP();
		$insertJS = empty($this->insertJS()) ? "" : $this->insertJS(); 
		$insertPHP = empty($this->insertPHP()) ? "" : $this->insertPHP();
		
		if($opt['includeJS'] !== ""){$this->assign($opt['includeJS'],$includeJS);}		
		if($opt['includeCSS'] !== ""){$this->assign($opt['includeCSS'],$includeCSS);}
		if($opt['includePHP'] !== ""){$this->assign($opt['includePHP'],$includePHP);}
		if($opt['insertJS'] !== ""){$this->assign($opt['insertJS'],$insertJS);}
		if($opt['insertPHP'] !== ""){$this->assign($opt['insertPHP'],$insertPHP);}
		
		$outContent = $this->replaceFileWithAssign($opt['tplFile'],$opt['sectionName']);
		if($opt["lReWrite"]){			
			if(is_file($opt['outPutFile'])){
				unlink($opt['outPutFile']);
			}
			file_put_contents($opt['outPutFile'],$outContent);			
		}else{
			if(!is_file($opt["outPutFile"])){
				file_put_contents($opt["outPutFile"],$outContent);
			}
		}
		if($opt['lShow']){
			//echo $outContent;
			include($opt["outPutFile"]); 
		}
	}
}
?>
