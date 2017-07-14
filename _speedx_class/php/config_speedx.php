<?php
//设置时区
date_default_timezone_set('Asia/Shanghai'); 

/*
*****************************************************
*************  全局环境配置  ************* 
*****************************************************
*/
//网站根目录
if(!defined("_DOCUMENTROOT_")){define("DOCUMENTROOT",rtrim($_SERVER['DOCUMENT_ROOT'],"/")."/");}
//speedx目录
if(!defined("_SPEEDX_")){define("SPEEDX",DOCUMENTROOT."speedx/");}
//用户网站根目录
if(!defined("_USERWEBSITE_")){define("USERWEBSITE",SPEEDX."userWebSite/");}
//当前项目根目录 
if(!defined("_PROJECTROOT_")){define("PROJECTROOT",USERWEBSITE."/vipedu/");}
//系统插件目录
if(!defined("_SYSPLUGINS_")){define("SYSPLUGINS",SPEEDX."_speedx_plugins/");}
//系统配置文件目录
if(!defined("_SYSCONFIG_")){define("SYSCONFIG",SPEEDX."_speedx_config/");}
//系统统一资源接口库
if(!defined("_SYSACTION_")){define("SYSACTION",SPEEDX."_speedx_actions/");}
//系统样式库
if(!defined("_SYSCSS_")){define("SYSCSS",SPEEDX."_speedx_css/");}
//系统数据库
if(!defined("_SYSDATABASE_")){define("SYSDATABASE",SPEEDX."_speedx_database/");}
//系统字体库
if(!defined("_SYSFONTS_")){define("SYSFONTS",SPEEDX."_speedx_fonts/");}
//系统JS插件库
if(!defined("_SYSJSLIB_")){define("SYSJSLIB",SPEEDX."_speedx_js_lib/");}
//系统PHP功能函数库
if(!defined("_SYSPHPLIB_")){define("SYSPHPLIB",SPEEDX."_speedx_php_lib/");}
//系统结构库
if(!defined("_SYSSTRUCTURE_")){define("SYSSTRUCTURE",SPEEDX."_speedx_structure/");}


/*
*****************************************************
*************   错误环境配置  *************
*****************************************************
*/

//$CONFIG_SPEEDX_ERR_SHOW = 0，speedx错误在运动时显示，用于开发调试环境
//$CONFIG_SPEEDX_ERR_SHOW = 1，speedx错误写入指定的错误文件 
$CONFIG_SPEEDX_ERR_SHOW = 0;


//指定错误日志文件
$CONFIG_SPEEDX_ERR_FILE = "/usr/local/error.log";



?>