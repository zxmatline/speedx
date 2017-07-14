<?php
include("../speedx/config.php");
include("../Speed备份/sysplugins/php/class.fileDownload.php");
$outDir = DOCUMENTROOT."uploads/";

if(isset($_GET['dirName'])){
	$outDir = DOCUMENTROOT.ltrim($_GET['dirName'],PROJECTROOT);
}

if(isset($_GET['name']) && !empty($_GET['name'])){
	$files = explode("|",$_GET['name']);
	if(count($files) == 1){
		$files = $files[0];
		$files = iconv("UTF-8","GBK",DOCUMENTROOT.ltrim($files,PROJECTROOT));
	}else{
		for($i = 0; $i < count($files); $i++){
			$files[$i] = iconv("UTF-8","GBK",DOCUMENTROOT.ltrim($files[$i],PROJECTROOT));
		}
	}
	$down = new filesDownload($outDir);
	$down->downloadFiles($files);
}else{
	header("Content-type:text/html;charset=utf-8");
	echo "没有选择要下载的文件或文件不存在!";
}
?>