<?php
	class group{
		private $requireEnv = array();
		private $linkJS = array();
		private $linkCSS = array();
		private $innerCSS = array();
		private $innerJS = array();
		private $innerJQ = array();		
		private $err = "";
		private $groupName = "";
		private $config = "";
		private $groupBodyFile = "";
		private $groupHtml = "";
		
		public function __structure($groupName = "",$config = ""){
			$this->groupName = $groupName;
			$this->config = $config;
			$this->analyze_config();
		}
		
		private function analyze_config(){		
			if(empty($this->groupName) || empty($this->config)){
				return;
			}
			if(empty($this->config)){
				$this->config = GROUP.$this->groupName."/config.php";
				$this->groupBodyFile = GROUP.$this->groupName."/".$this->groupName.".php";
			}
			
			$parms_group_pass = array();
			$parms_group_env = array();			
			
			if(!file_exists($this->config)){
				if(!file_exists($this->groupBodyFile)){
					$this->err = '组文件：｛'. $this->groupBodyFile . '}不存在!';					 
					return false;
				}
				$this->groupHtml = file_get_contents($this->groupBodyFile);
				return $this->groupHtml;
			}
			
			include $this->config;
			$parms_group_env = array();
			$parms_group_pass = array();
			$parms_group_env = register::_get("_require_env");
			$parms_group_pass = register::_get("_group_parms");

			if(!empty($parms_group_env)){
				if(isset($parms_group_env["require"])){$this->requireEnv = $parms_group_env["require"];}
				if(isset($parms_group_env["linkCSS"])){$this->linkCSS = $parms_group_env["linkCSS"];}
				if(isset($parms_group_env["linkJS"])){$this->linkJS = $parms_group_env["linkJS"];}
				if(isset($parms_group_env["innerCSS"])){$this->innerCSS = $parms_group_env["innerCSS"];}
				if(isset($parms_group_env["innerJQ"])){$this->innerJQ = $parms_group_env["innerJQ"];}
				if(isset($parms_group_env["innerJS"])){$this->innerJS = $parms_group_env["innerJS"];}
				
				if(isset($parms_group_env["groupBodyFile"])){
					$groupName = $parms_group_env["groupBodyFile"];
					if(!file_exists($groupName)){
						$this->err = '组件体：｛'. $this->groupName . '}不存在!';
						return false;
					}
					$this->groupHtml = file_get_contents($groupName);
				}
			}
		
			if(!empty($parms_group_pass)){				
				$this->groupHtml = $this->replace_with_assign($parms_group_pass);
			}
			
			unset($parms_group_env);
			unset($parms_group_pass);
			return true;
		}	

		private function replace_with_assign($vars){
			$keys = array_keys($vars);
			$vals = array_values($vars);

			foreach($keys as $key=>$value){
				$keys[$key] = '/\<\{\\$\s*'.$value.'\s*\}\>';
			}			
			return preg_replace($keys,$vals,$this->groupHtml);
		}
		
		public function reset(){
			$this->requireEnv = array();
			$this->linkJS = array();
			$this->linkCSS = array();
			$this->innerCSS = array();
			$this->innerJS = array();
			$this->innerJQ = array();
			$this->groupHtml = "";
			$this->err = "";
			$this->vars = array();
			$this->groupName = "";
			$this->config = "";
		}
		
		public function __get($var){
			return $this->$var;
		}

	}

?>


