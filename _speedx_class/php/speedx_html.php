<?php
/**
*** www.whxinteng.com
*** 作者：御风而行
*** 第一次测试时间:2016.11.22
*** 功能 ：模块化建设网站或APP
*** 开发目的：团队合作
**/
	class html{
		private $tpl = "";
		private $layout = "";
		private $layoutConfig = "";
		
		private $requireEnv = array();
		private $envJS = array();
		private $envCSS = array();
		
		private $linkJS = array();
		private $linkCSS = array();
		private $innerCSS = array();
		private $innerJS = array();
		private $innerJQ = array();
		
		private $html = "";
		private $body = "";		
		
		private $title = "";
		
		private $outputFile = "";
		
		private $groups = array();
		
		private $errno = 0;
		private $errinfo = "";
		
		private $vars = array();
		
		public function __construct($layoutConfig = ""){			
			$this->layoutConfig = $layoutConfig;
			$this->init();
		}
		
		private function init(){	
			$this->load_layout_config();
			$this->analyze_layout();
			$this->combine_group();
			$this->combine_html();
			$this->combine_vars();
		}
		
		public function create(){
			if(file_exists($this->outputFile)){
				@unlink($this->outputFile);				
			}
			file_put_contents($this->outputFile,$this->html);
		}
		public function html_test(){echo $this->html;}
		public function group_test(){}
		public function show(){
			echo "<pre>";
			echo htmlspecialchars($this->html);
		}
		
		/**
		**布局文件的配置文件，外部通过配置文件来设置html对象的属性。
		*/
		private function load_layout_config(){
			if(!file_exists($this->layoutConfig)){return;}
			include $this->layoutConfig;
			$parms_layout_pass = register::_get("_layout_parms");
			if(!empty($parms_layout_pass)){
				if(isset($parms_layout_pass["requireEnv"])){$this->requireEnv = $parms_layout_pass["requireEnv"];}
				if(isset($parms_layout_pass["linkJS"])){$this->linkJS = $parms_layout_pass["linkJS"];}
				if(isset($parms_layout_pass["linkCSS"])){$this->linkCSS = $parms_layout_pass["linkCSS"];}
				if(isset($parms_layout_pass["innerCSS"])){$this->innerCSS = $parms_layout_pass["innerCSS"];}
				if(isset($parms_layout_pass["innerJS"])){$this->innerJS = $parms_layout_pass["innerJS"];}
				if(isset($parms_layout_pass["linkJQ"])){$this->linkJQ = $parms_layout_pass["linkJQ"];}
				if(isset($parms_layout_pass["tpl"])){$this->tpl = $parms_layout_pass["tpl"];}
				if(isset($parms_layout_pass["layout"])){$this->layout = $parms_layout_pass["layout"];}
				if(isset($parms_layout_pass["outputFile"])){$this->outputFile = $parms_layout_pass["outputFile"];}
				if(isset($parms_layout_pass["groups"])){$this->groups = $parms_layout_pass["groups"];}
				if(isset($parms_layout_pass["vars"])){$this->vars = $parms_layout_pass["vars"];}	
				unset($parms_layout_pass);
			}						
		}
		
		/**
		**读入模板文件及布局文件内容到对象变量
		*/		
		private function analyze_layout(){
			if(empty($this->tpl)){
				$this->tpl = TPL."default.tpl";
			}
			
			if(!file_exists($this->tpl)){
				$errinfo = "模板:{".$this->tpl . "}不存在!";
				$this->seterr(5,$errinfo);
				return false;
			}			
			
			if(!file_exists($this->layout)){
				$this->seterr(1,$this->layout);
				return false;
			}
			
			$this->html = file_get_contents($this->tpl);
			$this->body = file_get_contents($this->layout);
			return true;
		}
		
		private function combine_group(){
			if(empty($this->groups)){return;}
			$groups = $this->groups;
			foreach($groups as $key => $value){
				$this->analyze_group($value);
			}
		}
		
		private function combine_html(){
			$this->analyze_env();			
			
			$replace = empty($this->envJS) ? "" : implode("\n",$this->envJS);
			$this->html = str_replace('<{$envJS}>',$replace,$this->html);
			
			$replace = empty($this->envCSS) ? "" : implode("\n",$this->envCSS);
			$this->html = str_replace('<{$envCSS}>',$replace,$this->html);
			
			$replace = empty($this->linkJS) ? "" : implode("\n",$this->linkJS);
			$this->html = str_replace('<{$linkJS}>',$replace,$this->html);
			
			$replace = empty($this->linkCSS) ? "" : implode("\n",$this->linkCSS);
			$this->html = str_replace('<{$linkCSS}>',$replace,$this->html);
			
			$replace = empty($this->innerCSS) ? "" : implode("\n",$this->innerCSS);
			$this->html = str_replace('<{$innerCSS}>',$replace,$this->html);
			
			$replace = empty($this->innerJS) ? "" : implode("\n",$this->innerJS);
			$this->html = str_replace('<{$innerJS}>',$replace,$this->html);
			
			$replace = empty($this->innerJQ) ? "" : implode("\n",$this->innerJQ);
			$this->html = str_replace('<{$innerJQ}>',$replace,$this->html);
			
			$replace = file_get_contents($this->layout);			
			$this->html = str_replace('<{$layout}>',$replace,$this->html);
			
		}
		
		private function combine_vars(){
			if(!empty($this->vars)){
				$vars = $this->vars;
				$keys = array_keys($vars);
				$vals = array_values($vars);
				foreach($keys as $key=>$value){
					$keys[$key] = '/\<\{\\$\s*'.$value.'\s*\}\>/';
				}			
				$this->html = preg_replace($keys,$vals,$this->html);
			}
		}
		
		private function analyze_group($group){
			$sysplugins = register::_get("_sysplugins");
			$envT = $group->requireEnv;
			if(!empty($envT)){
				foreach($envT as $value){
					if(!in_array($value,$this->requireEnv)){
						if(!isset($sysplugins[$value])){							
							$infor = "组[".$group."]中的应用环境{".$value."}没用定义！";
							$this->seterr(2,$infor);
							return false;
						}
						$this->requireEnv[] = $value;
					}					
				}
			}
			
			$this->addEnv($group,"linkCSS");
			$this->addEnv($group,"linkJS");
			$this->addEnv($group,"innerJS");
			$this->addEnv($group,"innerJQ");
			$this->addEnv($group,"innerCSS");			
			return true;			
		}
		
		private function analyze_env(){
			$sysplugins = register::_get("_sysplugins");
			if(!empty($this->requireEnv)){
				foreach($this->requireEnv as $value){
					if(isset($sysplugins[$value]["js"])){
						if(is_array($sysplugins[$value]["js"])){
							foreach($sysplugins[$value]["js"] as $val){
								$val = str_replace(DOCUMENTROOT,"/",$val);
								$this->envJS[] = '<script src="'.$val.'" ></script>';
							}
						}else{
							$val = str_replace(DOCUMENTROOT,"/",$sysplugins[$value]["js"]);
							$this->envJS[] = '<script src="'.$val.'" ></script>';
						}
					}
					
					if(isset($sysplugins[$value]["css"])){
						if(is_array($sysplugins[$value]["css"])){
							foreach($sysplugins[$value]["css"] as $val){
								$val = str_replace(DOCUMENTROOT,"/",$val);
								$this->envCSS[] = '<link rel="stylesheet" href="'.$val.'" />';
							}
						}else{
							$val = str_replace(DOCUMENTROOT,"/",$sysplugins[$value]["css"]);
							$this->envCSS[] = '<link rel="stylesheet" href="'.$val.'" />';
						}
					}
				}
			}
			
			if(!empty($this->linkCSS)){
				$linkCSS = array();
				foreach($this->linkCSS as $val){
					$val = str_replace(DOCUMENTROOT,"/",$val);
					$linkCSS[] = '<link rel="stylesheet" href="'.$val.'" />';
				}
				$this->linkCSS = $linkCSS;
			}
			
			if(!empty($this->linkJS)){
				$linkJS = array();
				foreach($this->linkJS as $val){
					$val = str_replace(DOCUMENTROOT,"/",$val);
					$linkJS[] = '<script src="'.$val.'" ></script>';
				}
				$this->linkJS = $linkJS;
			}
			
			if(!empty($this->innerCSS)){
				$innerCSS = array();
				foreach($this->$innerCSS as $val){					
					$val = file_get_contents($val);
					$innerCSS[] = $val;
				}
				$this->innerCSS = $innerCSS;
			}
			
			if(!empty($this->innerJS)){
				$innerJS = array();
				foreach($this->$innerJS as $val){					
					$val = file_get_contents($val);
					$innerJS[] = $val;
				}
				$this->innerJS = $innerJS;
			}
			
			if(!empty($this->innerJQ)){
				$innerJQ = array();
				foreach($this->$innerJQ as $val){					
					$val = file_get_contents($val);
					$innerJQ[] = $val;
				}
				$this->innerJQ = $innerJQ;
			}
		}
		
		private function addEnv($group,$type){
			$envT = $group->$type;
			if(!empty($envT)){
				foreach($envT as $val){
					if(!file_exists($val)){
						$info = "组[".$group."]中要求的环境文件{".$val."}不存在！";
						$this->seterr(3,$info);
						return false;
					}
					if(!in_array($val,$this->$type)){array_push($this->$type,$val);}
				}
			}
			return true;
		}
		
		private function setError($errno = 0,$errinfo = ""){
			if(!empty($errno) && empty($errinfo)){
				$this->seterr(-1);
				return;
			}
			$this->seterr();
		}

		public function __set($var,$val){
			switch($var){
				case "requireEnv" :
					$this->requireEnv[] = $val;
					break;
				case "envJS" :
				default:
			}
		}
		
		public function __get($var){
			$return = "";
			switch($var){
				case "html":
					$return = $this->html;
					break;
				default:
					$return = "";
			}
		}
		
		private function seterr($errno = 0,$errinfo = ""){
			switch($errno){
				case -1: 
					$this->errno = -1;
					$this->errinfo = "未被定义的错误信息";
					break;
				case 0:
					$this->errno = 0;
					$this->errinfo = "speedx组件没有接收到异常错误信息";
					break;
				case 1:
					$this->errno = 1;
					$this->errinfo = "布局文件:{".$errinfo."}不存在，文件不能解析";
					break;
				case 2:
					$this->errno = 2;
					$this->errinfo = $errinfo;//组环境未被定义
					break;
				case 3:
					$this->errno = 3;
					$this->errinfo = $errinfo;//组链接样式文件不存在
					break;
				case 4:
					$this->errno = 4;
					$this->errinfo = $errinfo;//"组件配置文件｛".$errinfo."｝不存在，组件初始化失败";
					break;
				case 5:
					$this->errno = 5;
					$this->errinfo = $errinfo;//模板文件不存在;
					break;
				case 6:
				case 7:
				case 8:
				case 9:
				case 10:
				default:
			}
		}
		
		private function geterr(){
			$returnErr = array();
			$returnErr[0] = $this->errno;
			$returnErr[1] - $this->errinfo;
			return $returnErr;
		}
	}

?>