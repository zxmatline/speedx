<?php
class filesDownload {
	private $outDir = "";
	
	public function construct($dir = "./"){
		$this->outDir = rtrim($dir,"/")."/";
	} 
	
/*****************************文件下载（单文件直接下载，多文件打包压缩后下载）**********************************/	
	public function downloadFiles($downloadFiles,$deleteSource = true){				
		if(is_array($downloadFiles)){
			$downzip = new ZipArchive(); 
			$newzipName = $this->outDir. $this->createNewFileName("newZip_",".zip");
			if(file_exists($newzipName)){@unlink($newzipName);}
			
			$downzip->open($newzipName,ZipArchive::OVERWRITE); //创建一个新的ZIP文件
			
			foreach($downloadFiles as $subFiles){
				if(!file_exists($subFiles)){
					header("Content-type:text/html;charset=utf-8");
					echo "文件:".basename($subFiles)."不存在，无法下载";
					return;
				}
				$pathinfo = explode("/",$subFiles);
				$outputFile = array_pop($pathinfo);
				$downzip->addFile($subFiles,$outputFile);//第一个参数是要加入到zip文件里的实体文件，第二个参数是压缩到文件里的新文件名
			}
			$downzip->close();
			$this->downOutput($newzipName);
			if($deleteSource){
				@unlink($newzipName);
			}
				
		}else{

			if(!file_exists($downloadFiles)){
				header("Content-type:text/html;charset=utf-8"); 
				echo "没有该文件，无法下载";
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
		$pathinfo = explode("/",$downloadFiles);
		$outputFile = array_pop($pathinfo);
		//$fileName = DOCUMENTROOT.ltrim($CONFIG[$_POST['tplName']],"/").$outputFile;
		//$outputFile = iconv("UTF-8","GBK",$outputFile);		
		header("Content-type: application/octet-stream");  
		header("Accept-Ranges: bytes");  
		header("Accept-Length:".$fileSize); 
		header('Content-Transfer-Encoding: binary' ); 
		header("Content-Disposition: attachment; filename=".$outputFile);
		
		$buffer=1024; //建议这个缓冲不要设的过大，最多4个K
		$readCount = 0;		
							
		while(!feof($fp) && $readCount < $fileSize){ 
			$readFileContent = fread($fp,$buffer); 
			$readCount += $buffer; 
			echo $readFileContent; 
		} 
		fclose($fp); 	
	}
	
/********************产生随机不重复的新的文件名********************* */	
	private function createNewFileName($forword = "new_",$ext = "zip"){		
		$newExt = substr($ext,strrpos($ext,'.'));
		$newzipName = $forword.date("YmdHis").mt_rand(10,999).mt_rand(10,999).mt_rand(10,999).$newExt;		
		return $newzipName;
	}
}
?>