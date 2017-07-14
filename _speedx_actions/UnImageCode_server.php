<?php
session_start();
require_once "../Speed备份/publicClass_php/class.createCheckImg.php";

//if($_SERVER["HTTP_HOST"] !== "localhost"){return false;}//验证请求来自本站
$codeImage = new createImageCode("./font");	
$codeImage->outputImage();

$type  = $_GET['type'];
$_SESSION['imgCode'] = $codeImage->getCode();
$_SESSION['imgCode_sid'] = "loginsid$".time()."$".mt_rand(1000,9999);
$_SESSION['imgCreateTime'] = time();
?>
