<?php
include("../speedx/config.php");

$state = array();

if(isset($_POST["op"]) && isset($_POST['tplName']) && isset($_POST["name"])){
	if(!isset($CONFIG[$_POST['tplName']])){
		$state[] = "err";
		$state[] = "模块不存在,无法获取文件";
		echo implode("|",$state);
		return;
	}
	
	if(!isset($_POST['name'])){
		$state[] = "err";
		$state[] = "没有指定文件，无法执行操作";
		echo implode("|",$state);
		return;
	}
	
	$delFile =$_POST['name'];
	
	switch($_POST["op"]){
	case "erase":
		$pathinfo = explode("/",$delFile);
		$delFile = array_pop($pathinfo);
		$fileName = DOCUMENTROOT.ltrim($CONFIG[$_POST['tplName']],"/").$delFile;
		$fileName = iconv("UTF-8","GBK",$fileName);
		if(!file_exists($fileName)){
			$state[] = "err";
			$state[] = "文件".$fileName."不存在，不能执行删除操作";
			echo implode("|",$state);
			return;
		}
		@unlink($fileName);
		$state[] = "success";
		$state[] = "删除成功";
		echo implode("|",$state);
		break;
	case "delete":
		if(!is_array($delFile)){
			$state[] = "err";
			$state[] = "参数传递错误";
			echo implode("|",$state);
			return;
		}
		
		foreach($delFile as $value){
			$pathinfo = explode("/",$value);
			$delFile = array_pop($pathinfo);
			$fileName = DOCUMENTROOT.ltrim($CONFIG[$_POST['tplName']],"/").$delFile;
			$fileName = iconv("UTF-8","GBK",$fileName);
			
			if(!file_exists($fileName)){
				$state[0] = "err";
				$state[] = "文件".$fileName."不存在，不能执行删除操作";
			}else{
				@unlink($fileName);
			}			
		}
		
		if(count($state) <= 0){
			$state[] = "success";
			$state[] = "删除成功";
		}		
		echo implode("|",$state);
		return;
		break;
	default:
	}
	
}else{
	$state[] = "err";
	$state[] = "参数不正确，无法执行操作";
	echo implode("|",$state);
	return;
}
?>
