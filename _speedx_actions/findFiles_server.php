<?php
include("../speedx/config.php");
include("../Speed备份/sysplugins/php/class.findFiles.php");

$new = array();
if(isset($_POST['tplName'])){
	if(isset($_POST['allowedExt'])){
		$ext = $_POST['allowedExt'];
	}else{
		$ext = array("jpg","png","gif","bmp");
	}
	$tplName = $_POST['tplName'];
	if(!isset($CONFIG[$tplName])){
		$new[] = "err";
		$new[] = "模块不存在,无法获取文件";
	}else{
		$find = new findFiles;
		$find->extension = $ext;
		$oDir = DOCUMENTROOT.ltrim($CONFIG[$tplName],"/");
		$find->findFileByExtention($oDir);
		$resault = $find->resault;
		if(empty($resault)){
			$new[] = "err";
			$new[] = "没有查询到相关文件";
		}else{
			foreach($resault as $val){
				$tmp  = PROJECTROOT.ltrim($val,DOCUMENTROOT); 
				$new[] = mb_convert_encoding($tmp,"UTF-8","GBK"); 
			}
			array_unshift($new,"success");		
		}
	}
}else{
	$new[] = "err";
	$new[] = "没有指定模块,无法获取文件";
}
echo implode("|",$new);	
?>