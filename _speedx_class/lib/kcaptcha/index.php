<?php
error_reporting (E_ALL);
include('kcaptcha.php');
include_once("../../_speedx_config/_speedx_public_config.php");
include_once(SYSPHPLIB."class.register.php");
include_once(SYSCONFIG."_speedx_plugins_config.php");
include_once(SYSDATABASE."startSession.php");
$captcha = new KCAPTCHA();

if($_REQUEST[session_name()]){
	$_SESSION['captcha_keystring'] = $captcha->getKeyString();
}

?>