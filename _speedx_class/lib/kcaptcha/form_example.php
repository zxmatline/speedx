<?php
include('kcaptcha.php');
include_once("../../_speedx_config/_speedx_public_config.php");
include_once(SYSPHPLIB."class.register.php");
include_once(SYSCONFIG."_speedx_plugins_config.php");
include_once(SYSDATABASE."startSession.php");
?>
<form action="" method="post">
<p>Enter text shown below:</p>
<p><img src="./?<?php echo session_name()?>=<?php echo session_id()?>"></p>
<p><input type="text" name="keystring"></p>
<p><input type="submit" value="Check"></p>
</form>
<?php
if(count($_POST)>0){
	if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring']){
		echo "Correct";
	}else{
		echo "Wrong";
	}
}
unset($_SESSION['captcha_keystring']);
?>