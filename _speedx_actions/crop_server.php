<?php
include("../speedx/config.php");
//include(DOCUMENTROOT."speed/sysplugins/php/class.cropper.php");
include("../Speed备份/sysplugins/php/class.cropper.php");

$state = array();

if(!isset($_POST["op"]) || !isset($_POST["data"])){		
	$state[] = "err";
	$state[] = "参数不正确!";
	echo implode("|",$state);
}

$data = json_decode($_POST["data"],true);
$data["curImageSrc"] = DOCUMENTROOT.ltrim($data["curImageSrc"],PROJECTROOT);
$data["canvasImgSrc"] = DOCUMENTROOT.ltrim($data["canvasImgSrc"],PROJECTROOT);
$crop = new cropper(dirname($data["curImageSrc"]));

if(!is_file($data["curImageSrc"])){
$state[] = "err";
$state[] = "当前目标文件不存在！";
echo implode("|",$state);
return;
}

if(!is_file($data["canvasImgSrc"])){
$state[] = "err";
$state[] = "目标源文件不存在！";
echo implode("|",$state);
return;
}


if(isset($_POST["op"])){
	switch($_POST["op"]){
	case "rotate":
		$curImg = $data["curImageSrc"];
		$rotate = $data["rotate"];		
		$crop = new cropper(dirname($curImg));
		$newImg = $crop->rotate($curImg,$rotate);
		$newImg = PROJECTROOT.ltrim($newImg,DOCUMENTROOT);
		if(is_file($curImg)){@unlink($curImg);}
		
		$state[] = "success";
		$state[] = $newImg;
		echo implode("|",$state);
		return;
		break;
	case "flip":
		$curImg = $data["curImageSrc"];
		$flipX = $data["flipX"];
		$flipY = $data["flipY"];		
		$crop = new cropper(dirname($curImg));
		$newImg = $crop->flip($curImg,$flipX,$flipY);
		$newImg = PROJECTROOT.ltrim($newImg,DOCUMENTROOT);
		if(is_file($curImg)){@unlink($curImg);}
		$state[] = "success";
		$state[] = $newImg;
		echo implode("|",$state);
		return;
		break;
	case "filter":
		//$img = $data["canvasImgSrc"];
		$curImg = $data["curImageSrc"];
		$filterType = $data["filterType"];
		$crop = new cropper(dirname($curImg));
		$newImg = $crop->filter($curImg,$filterType);
		if(file_exists($newImg)){
			$newImg = PROJECTROOT.ltrim($newImg,DOCUMENTROOT);
			if($data["curImageSrc"] !== $data["canvasImgSrc"]){
				if(is_file($curImg)){@unlink($curImg);}	
			}					
			$state[] = "success";
			$state[] = $newImg;
			echo implode("|",$state);
			return;
		}else{
			$state[] = "err";
			$state[] = "滤镜执行错误.";
			echo implode("|",$state);
			return;
		}		
		break;
	case "crop":
		$curImg = $data["curImageSrc"];		
		$newFile = $crop->cropper($data);
		if(file_exists($newFile)){
			$newFile = PROJECTROOT.ltrim($newFile,DOCUMENTROOT);
			if($data["curImageSrc"] !== $data["canvasImgSrc"]){
				if(is_file($curImg)){@unlink($curImg);}	
			}					
			$state[] = "success";
			$state[] = $newFile;
			echo implode("|",$state);
			return;
		}else{
			$state[] = "err";
			$state[] = "裁剪执行错误.";
			echo implode("|",$state);
			return;
		}
		break;
	case "cancel":
		$img = $data["canvasImgSrc"];
		$curImg = $data["curImageSrc"];
		if($img !== $curImg){
			if(is_file($curImg)){@unlink($curImg);}
		}
		$state[] = "success";
		$state[] = "取消成功";
		echo implode("|",$state);
		return;
		break;
	default:
		$state[] = "err";
		$state[] = "未知操作或该操作功能尚未开放";
		echo implode("|",$state);
		return;
		break;
	}
};
