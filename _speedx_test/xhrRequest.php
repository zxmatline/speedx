<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
<?php
/*	
	header('MyHeader:MyValue');
	if(isset($_GET["MyHeader"])){
		$get = $_GET["MyHeader"];
		echo "这是从服务器发回的一个消息:".$get;
	}else{
		echo "这是从服务器发回的一个消息:";
	}
*/	
	$post = $_POST;
	$get = $_GET;
	var_dump($get);
	
	$pid = pcntl_fork();
?>
</body>
</html>