<?php
include("../speedx/config.php");
$out_dir = DOCUMENTROOT."uploads/";
if(!is_dir($out_dir)){mkdir($out_dir);}


if(isset($_FILES["myUploadFiles"]))
{
	$error = $_FILES["myUploadFiles"]["error"];
	if(!is_array($_FILES["myUploadFiles"]["name"])) //single file
	{
 	 	$fileName = $_FILES["myUploadFiles"]["name"];
		
		$pathinfo = pathinfo($fileName);
		$newFileName = "new_".time().mt_rand(0,999).mt_rand(0,999).mt_rand(0,999).".".$pathinfo['extension'];
 		@move_uploaded_file($_FILES["myUploadFiles"]["tmp_name"],$out_dir.$newFileName);
	}
	else 
	{
	  $fileCount = count($_FILES["myUploadFiles"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myUploadFiles"]["name"][$i];
		@move_uploaded_file($_FILES["myUploadFiles"]["tmp_name"][$i],$out_dir.$newFileName);
	  }
	
	}
	if(!is_file($out_dir.$newFileName)){
		die("err");
	}else{
		
    	echo PROJECTROOT."uploads/".basename($newFileName);
	}
 }
 ?>
