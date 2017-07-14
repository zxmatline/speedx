<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript" type="text/javascript" src="../../../Speed备份/thirdPlugins/domain/jquery_sys_Lib/jquery-1.11.1.min.js"></script>
<script language="javascript" type="text/javascript" src="../../../Speed备份/thirdPlugins/domain/jquery_use_lib/myUDFjs.js"></script>
<link href="../../../Speed备份/thirdPlugins/domain/webcss_Lib/indexCSS.css" rel="stylesheet" type="text/css" />
<link href="../../../Speed备份/thirdPlugins/domain/webcss_Lib/ui-dialog.css" rel="stylesheet" type="text/css">
<script src="../../../Speed备份/thirdPlugins/domain/jquery_use_lib/dialog-min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style type="text/css">

</style>
<script type="text/javascript">
	$(function(){
	  var $imgs = [
				  "images_first/ImageShowblock/show01.gif",
				  "images_first/ImageShowblock/show02.gif",
				  "images_first/ImageShowblock/show03.gif",
				  "images_first/ImageShowblock/show04.gif"
				  ];		  
	  $("#showImages").createImageContainter($imgs,"#showImages");
	  $("#showImages").autoPlay();
	  $("#mainMenu").currentContain();	
	})
</script>
<title>小明在线_首页</title>
</head>

<body>
<?php

/*
  //域名查询结果有三个状态：
  //210：可以注册
  //211：已经注册
  //212：参数错误
  //214：未知错误
 */





/* 开始声明变量 */
$domain_last_all = array('com', 'cn', 'net');
$domain_last = array();   //获取到的域名后缀数组array();
$domain;           //获取到的域名
/* 结束声明变量 */

//获取到的域名
$domain = $_POST['domain'];

/////////////////////////////////////
//调试专区
if ($domain != "" && isset($domain)) {
    $domain_last = get_domain_last($domain_last_all);
    query_domain($domain, $domain_last);
    //print_r($domain_last);
} else {
    echo '未输入域名！';
}

//////////////////////////////////////


/* 开始申明函数 */

//循环获取到的域名后缀经过判断后放入数组
function get_domain_last($domain_last_all) {
    //循环预设域名后缀
    $post = $_POST;
    $domain_last = array();
    foreach ($domain_last_all as $value) {
        $domain = $post[$value];
        //将非空的域名后缀放入数组$domain_lase
        if ($domain != "") {
            $domain_last[] = $domain;
        }
    }
    return $domain_last;
}

//对传过来的查询条件，进行循环查询
function query_domain($domain, $domain_last) {
    if ($domain != "" && isset($domain)) {
        foreach ($domain_last as $value) {
            //每次初始化为空值
            $domain_string="";
            $domain_string=$domain.$value;
            echo $domain;
            echo $value;
            $xml_arr=query($domain_string);
            show($xml_arr);
        }
    }
}

//查询接口
function query($domain) {
    $do = "http://panda.www.net.cn/cgi-bin/check.cgi?area_domain=" . $domain;
    $xml_data = file_get_contents($do);
    $xml_arr = (array) simplexml_load_string($xml_data);
    return $xml_arr;
}
//显示接口
function show($result_arr){
    $returncode=$result_arr['returncode'];
    $key=$result_arr['key'];
    $original=$result_arr['original'];
    $status=  substr($original,0,3);
    //申明返回值
    $result;
    if($status=="210"){
        echo "可以注册";
    }else if($status=="211"){
        echo "已经注册";
    }else if($status=="212"){
        echo "参数错误";
    }
}

//打印变量的函数  后期可删除
function dump($socu) {
    print_r($socu);
}

/* 结束申明函数 */
?>
</body>
</html>