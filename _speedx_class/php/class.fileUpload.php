<?php
/////////////////////////////////////////////////////////////////////
////////////////////////********开始*********/////////////////////////
////////////////////////***文件的上传与下载****/////////////////////////

class filesUpload {
	private $upLoadDir = "../tempFiles";
	private $upLoadFileSize = 1000000;
	private $upLoadFileType = array("jpg","gif","png");
	private $autoRename = true;		
	
	private $uploadFileName = ""; //上传文件域的域名
	private $errMessage=array("errNum" => array(),"errMsg" => array());//存放有关的错误信息
	
	
	public function __set($varName,$varValue){
		if(in_array($varName,array("upLoadDir","upLoadFileSize","uploadFileName","upLoadFileType","autoRename"),true)){
			$this->$varName = $varValue;	
		}
	}
	
	
	public function __get($varName){
		if(in_array($varName,array("upLoadDir","upLoadFileSize","uploadFileName","upLoadFileType","autoRename","errMessage"),true)){
			return $this->$varName;			
		}
	}
	
/*************************** 文件上传 **************************************/
	function upload(){		
		
		$this->errMessage=array("errNum" => array(),"errMsg" => array());		
		
		if($this->checkUploadDir() !== 0){
			$this->setErrMsg($this->checkUploadDir(),"",0);
			return;
		}
		
		$uploadFile = $_FILES[$this->uploadFileName]['name'];
		$temp_fileName = $_FILES[$this->uploadFileName]['tmp_name'];
		$uploadFileSize = $_FILES[$this->uploadFileName]['size'];
		$errMsg = $_FILES[$this->uploadFileName]['error'];				
		
		if(is_array($uploadFile)){ //多文件上传处理			
			for($tmp = 0;$tmp < count($uploadFile); $tmp++){
				$returnNum = $this->checkUploadFile($uploadFile[$tmp],$temp_fileName[$tmp]);
				if( $returnNum !== 0 )					
					$this->setErrMsg($returnNum,$uploadFile[$tmp],$tmp);
				else{
					$newFileName = $this->upLoadDir ."/".$this->getNewFileName($uploadFile[$tmp]);	
					$oldFileName = $this->upLoadDir ."/".$uploadFile[$tmp];	
										
					if(!file_exists($newFileName)){												
						@move_uploaded_file($temp_fileName[$tmp],$newFileName);					
						if(!$this->autoRename){	
							if(!file_exists($this->userIconv($oldFileName)))					
								rename($newFileName, $this->userIconv($oldFileName));						
							}
						}
					$this->setErrMsg($returnNum,$uploadFile[$tmp],$tmp);			
						
					}											
			}
		}else{ //单文件上传处理			
			$returnNum = $this->checkUploadFile($uploadFile,$temp_fileName);							
			if( $returnNum != 0){
				$this->setErrMsg($errMsg,$uploadFile,0);
			}else{
				$newFileName = $this->upLoadDir ."/".$this->getNewFileName($uploadFile);	
				$oldFileName = $this->upLoadDir ."/".$uploadFile;
				
				if(!file_exists($newFileName)){	
					@move_uploaded_file($temp_fileName,$newFileName);
					if(!$this->autoRename){	
						if(!file_exists($this->userIconv($oldFileName)))				
							rename($newFileName, $this->userIconv($oldFileName));				
						}
					}
				$this->setErrMsg($returnNum,$uploadFile,0);
				}				
		}
	}
	
/**************************文件下载（单文件直接下载，多文件打包压缩后下载）**********************************/	
	public function downloadFiles($downloadFiles){				
		if(is_array($downloadFiles)){
			$downzip = new ZipArchive(); 
			$newzipName = strtolower(date("YmdHis"). str_pad(strval(mt_rand(10,999)),5,"0",STR_PAD_LEFT).".zip");		
			
			if(!file_exists($newzipName)){
				$downzip->open($newzipName,ZipArchive::OVERWRITE); //创建一个新的ZIP文件
				
				foreach($downloadFiles as $subFiles){
					$subFiles = $this->userIconv($subFiles);
					$downzip->addFile($subFiles,basename($subFiles));//第一个参数是要加入到zip文件里的实体文件，第二个参数是压缩到文件里的新文件名
				}
				$downzip->close();
				$this->downOutput($newzipName);
				unlink($newzipName);	
			}				
		}
		else{
			$downloadFiles = $this->userIconv($downloadFiles);	
			//echo $downloadFiles;				 					 
			if(!file_exists($downloadFiles)){ 
				echo $downloadFiles . "<br/>";
				echo "没有该文件!"; 
				return ; 
			}
			$this->downOutput($downloadFiles);	  
		}		
		

	}
	
/**************************文件下载（向客户端输出）**********************************/		
	private function downOutput($downloadFiles){
		ob_end_clean(); //清除缓冲区，这句一定要加，不然下载的文件会失效！！
		$fp = fopen($downloadFiles,"r"); 
		$fileSize = filesize($downloadFiles); 
		//header("Content-type:text/html;charset=utf-8");	
					  
		header("Content-type: application/octet-stream");  
		header("Accept-Ranges: bytes");  
		header("Accept-Length:".$fileSize); 
		header('Content-Transfer-Encoding: binary' ); 
		header("Content-Disposition: attachment; filename=".basename($downloadFiles));
		
		$buffer=1024; //建议这个缓冲不要设的过大，最多4个K
		$readCount = 0;		
							
		while(!feof($fp) && $readCount < $fileSize){ 
			$readFileContent = fread($fp,$buffer); 
			$readCount += $buffer; 
			echo $readFileContent; 
		} 
		fclose($fp); 	
	}

	
/*****************************设置错误号和错误信息************************************/	
	private function setErrMsg($errNumber,$fileName,$tmpNum){
		
		switch($errNumber){
		case -5:
			$this->errMessage["errNum"][$tmpNum]=-5;
			$this->errMessage["errMsg"][$tmpNum]="文件域发生致命错误，不能上传！！";	
			break;
		case -4:
			$this->errMessage["errNum"][$tmpNum]=-4;
			$this->errMessage["errMsg"][$tmpNum]="表单文件域不正确！！";	
			break;
		case -3:
			$this->errMessage["errNum"][$tmpNum]=-3;
			if(empty($fileName))
				$this->errMessage["errMsg"][$tmpNum]="文件不存在或未被上传（未选择文件）!!";
			else
				$this->errMessage["errMsg"][$tmpNum]="非法文件：[{$fileName}]!!";
			break;
		case -2:
			$this->errMessage["errNum"][$tmpNum]=-2;
			if(!isset($tempFile) || empty($tempFile))
				$this->errMessage["errMsg"][$tmpNum]="文件未被上传或文件类型不合法!!";
			else
				$this->errMessage["errMsg"][$tmpNum]="文件：[{$tempFile}]类型不合法!!";
			break;
		case -1:
			$this->errMessage["errNum"][$tmpNum]=-1;
			$this->errMessage["errMsg"][$tmpNum]="上传目录不存在或不能创建！";	
			break;
		case 0:
			$this->errMessage["errNum"][$tmpNum]=$errNumber;
			$this->errMessage["errMsg"][$tmpNum]="文件：[{$fileName}]上传正常!!";
			break;
		case 1: 
			$this->errMessage["errNum"][$tmpNum]=$errNumber;
			$this->errMessage["errMsg"][$tmpNum]="文件：[{$fileName}]超过了php.ini中upload_max_filesize的设定，上传失败！";			
			break;						
		case 2:
			$this->errMessage["errNum"][$tmpNum]=$errNumber;
			$this->errMessage["errMsg"][$tmpNum]="文件：[{$fileName}]超过了html表单中MAX_FILE_SIZE的设定,没有上传成功！";
			break;
		case 3:
			$this->errMessage["errNum"][$tmpNum]=$errNumber;
			$this->errMessage["errMsg"][$tmpNum]="文件：[{$fileName}]只有部分被上传！";
			break;
		case 4:
			$this->errMessage["errNum"][$tmpNum]=$errNumber;
			$this->errMessage["errMsg"][$tmpNum]="文件：[{$fileName}]没有被上传！";
			break;
		default:
			$this->errMessage["errNum"][$tmpNum]=$errNumber;
			$this->errMsg = "未知错误！！";
		}
		
	}
	
/********************中文文件名转码的问题解决（UTF-8向GB2312转换）********************* */	
	private function userIconv($fileName){
		$baseName = substr($fileName,0,strrpos($fileName,"."));
		$baseName = iconv("UTF-8","GB2312",$baseName);
		$extName = substr($fileName,strrpos($fileName,"."));
		return $baseName . $extName;	
	}
	

/********************产生随机不重复的新的文件名********************* */	
	private function getNewFileName($ext){		
		$newExt = substr($ext,strrpos($ext,'.'));
		$newFileName = strtolower(date("YmdHis"). str_pad(strval(mt_rand(100,999)),5,"0",STR_PAD_LEFT).$newExt);
		return $newFileName;
	}

/****************检查上传目录件的合法性**************/	
	private function checkUploadDir(){
		
		if(!is_dir($this->upLoadDir)) return -1;
		
		if(empty($this->uploadFileName)) return -4;	
		
		if(!isset($_FILES[$this->uploadFileName])) return -5;		
		
		return 0;
	}
	
/****************检查上传文件的合法性，是不是允许上传的文件**************/	
	private function checkUploadFile($fileName,$uploadTempFile){
		$fileNameInfo = pathinfo(strtolower($fileName));		
				
		if(!is_uploaded_file($uploadTempFile)) return -3;		
		
		if(!isset($fileName)|| empty($fileName) || !in_array($fileNameInfo['extension'],$this->upLoadFileType,true))			
			return -2;
					
		return 0;
	}
	
}

/////////////////////////////////////////////////////////////////////
////////////////////////********结束*********////////////////////////
/////////////////////////////////////////////////////////////////////
?>