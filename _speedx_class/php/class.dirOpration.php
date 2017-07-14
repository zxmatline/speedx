<?php
/////////////////////////////////////////////////////////////////////
////////////////////////********开始*********/////////////////////////
////////////////////////***目录的复制与删除****//////////////////////////
/////////////*****技术交流群，刚建立不久，多多包含： Q群：90884953*****/////////
class dir_Opration {		
		private $anjing = true; //true为向浏览器输出操作结果，false为安静输出；
		private $sourceDir = "";
		private $objDir = "";
		private $delDir = "";
		private $nType = 1;
/*  $nType的值的几种用法:
  	1: 将源目录中的文件全部复制到目标目录中，包含子目录中的全部文件；
  	2：将源目录中的文件全部复制到目标目录中，不包含子目录及其文件；
	3：删除本目录中的所有文件，包含子目录；
  	4: 删除本目录中的所有文件，不包含子目录;
*/		
	function __construct($ssDir = "",$ooDir = "",$ddDir = "",$nnType = 1,$aj = true){
			$this->anjing = $aj;
			$this->sourceDir = $ssDir;
			$this->objDir = $ooDir;
			$this->delDir = $ddDir;
			$this->nType = $nnType;
		
		}					
	public function __set($varName,$varValue){ //该方法必须是public类型
		if($varName == "nType"){
				if($varValue < 1 || $varValue > 4)					
					return;
			}
		 if($varName == "sourceDir"){
				if(!is_dir($varValue))
					return;
			 }
			 
		if($varName == "delDir"){
				if(!is_dir($varValue))
					return;
			 }
		if($varName == "anjing" && !is_bool($varValue))
			return;
			
		 $this->$varName = $varValue;				
	}
		
			
		public function execute(){
			switch($this->nType){
					case 1:
						$this->copyDir($this->sourceDir,$this->objDir,$this->nType);
						break;
					case 2:
						$this->copyDir($this->sourceDir,$this->objDir,$this->nType);
						break;
					case 3:
						$this->deleteDir($this->delDir,$this->nType);
						break;
					case 4:
						$this->deleteDir($this->delDir,$this->nType);
						break;
					default:					
						echo "参数传值非法！！";
					}			
			}
					
		private function deleteDir($sDir,$type=3){
				if(is_dir($sDir)){
						$dir_handle = @opendir($sDir);
						while($readFile = readdir($dir_handle)){
							if($readFile != "." && $readFile != ".."){
								$fullFileName = $sDir . "/" . $readFile;								
								if(is_file($fullFileName)){										
										unlink($fullFileName);
										if($this->anjing) 
											echo "【删除文件：】" . $fullFileName . "<br/>";
									}else if(is_dir($fullFileName) && $type == 3){
											$this->deleteDir($fullFileName);										
										}
							}
						}
							closedir($dir_handle);							
							//echo $sDir ."<br/>";
						if($type == 3){
							rmdir($sDir);
							if($this->anjing) 
								echo "【删除目录：】" . $sDir . "<br/>";
						}
					}else return false;
					
					return true;					
			} 		
/**************将源目录中的文件全部复制到目标目录中，包含全部子目录文件*******************/					
		private function copyDir($sDir,$oDir, $type=1){
			
				if($this->isDir($sDir,$oDir)){
						$dir_handle = @opendir($sDir);
						while($readFile = readdir($dir_handle)){
								if($readFile != '.' && $readFile != '..'){
										$temp_sDir = $sDir . "/" . $readFile;
										$temp_oDir = $oDir . "/" . $readFile;
										
										if(is_file($temp_sDir)){
											if($this->anjing) 
												echo "【文件：】" . $temp_sDir ."<br/>";
											copy($temp_sDir,$temp_oDir);
										 }else if($type == 1){											
											if($this->anjing) {
												echo "【源目录：】" . $temp_sDir . "<br/>";
												echo "【目标目录：】" .$temp_oDir ."<br/>";
											}
											//mkdir($temp_oDir);
											$this->copyDir($temp_sDir,$this->userIconv($temp_oDir));
										}
									}	
							}
							closedir($dir_handle);	
					}	
			}
/********************中文文件名转码的问题解决（UTF-8向GB2312转换）********************* */	
	private function userIconv($fileName){
		$baseName = substr($fileName,0,strrpos($fileName,"."));
		$baseName = iconv("UTF-8","GB2312",$baseName);
		$extName = substr($fileName,strrpos($fileName,"."));
		return $baseName . $extName;	
	}
				
			
   /* 判断源目录是否存在，目标目录是否是一个有效的目录 */			
		private function isDir($sourDir,$obDir){
			if(!is_dir($sourDir)){
					echo "不是一个有效的源目录！！";
					return false;
				}
			if(!is_dir($obDir)){
				if(is_file($obDir)){
						echo "不是一个有效的目标目录名";
						return false;
					}
					mkdir($obDir);
				}
			return true;
		}			
			
}
/////////////////////////////////////////////////////////////////////
////////////////////////********结束*********////////////////////////
/////////////////////////////////////////////////////////////////////

?>