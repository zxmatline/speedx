<?php
class findFiles {		
		private $includesubdir = false;
		private $extension = array("png","gif","jpg");
		private $resault = array();
		private $findFiles = array();
		private $err = "success";
		
	public function __set($varName,$varValue){$this->$varName = $varValue;}	
	public function __get($varName){return $this->$varName;}
	
	public function findFileByName($sDir){
	  if(empty($this->findFiles)){
		  $this->err = "没有指定查找的文件";
		  return false;
	  }
	  
	  if($this->isDir($sDir)){
		$dir_handle = @opendir($sDir);
		while($readFile = readdir($dir_handle)){
		  if($readFile != '.' && $readFile != '..'){						
			$temp_sDir = rtrim($sDir,"/") . "/" . $readFile;
			$temp_sDir = iconv('utf-8', 'gb2312', $temp_sDir);						
			if(is_file($temp_sDir)){
				if(in_array($readFile,$this->findFiles)){
				  array_push($this->resault,$temp_sDir);
				}							
			 }else{
				if($this->includesubdir){								
					$this->findFileByName($temp_sDir);
				}
			}
		  }	
		}
		  closedir($dir_handle);	
	  }	
	  return true;	
	}
	
	public function findFileByExtention($sDir){	 
	  if(empty($this->extension)){
		  $this->err = "没有指定文件类型";
		  return false;
	  }
	  if($this->isDir($sDir)){
		$dir_handle = @opendir($sDir);
		if(!$dir_handle){
			$this->err = "目录打开错误";
			return false;
		}
		while($readFile = readdir($dir_handle)){
		  if($readFile != '.' && $readFile != '..'){						
			$temp_sDir = rtrim($sDir,"/"). "/" . $readFile;	
			//$temp_sDir = iconv('utf-8', 'gb2312', $temp_sDir);							
			if(is_file($temp_sDir)){
				$ext = pathinfo($temp_sDir);
				$ext = $ext['extension'];
				
				if($this->extension == "*"){
					array_push($this->resault,$temp_sDir);
				}else{
					if(in_array($ext,$this->extension)){
						$temp_sDir = mb_convert_encoding($temp_sDir,"UTF-8","GBK");
						array_push($this->resault,$temp_sDir);
					}							
				}
			 }else{
				 if($this->includesubdir){								
					$this->findFileByExtention($temp_sDir);
				}			
			}
		  }	
		}
		  closedir($dir_handle);	
	  }		
		return true;
	}
   /* 判断源目录是否存在，目标目录是否是一个有效的目录 */			
	private function isDir($sourDir){
		if(is_dir($sourDir) && file_exists($sourDir)){
			return true;
		}else{
			$this->err = "不是一个有效的源目录！！";				
			return false;
		}
		return true;
	}
	
}
?>