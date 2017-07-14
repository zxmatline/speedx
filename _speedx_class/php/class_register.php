<?php
/***
*******************************************************************
****   全局变量注册类  ****
*******************************************************************
*/

class speedx_var_register{
	private static $_sys_vars = array();
	private static $_layout_parms = array();
	private static $_group_parms = array();
	private static $_require_env = array();	
	
	public static function _set($var,$val){
		$temp = $val;
		self::$_sys_vars[$var] = &$temp;
	}
	
	public static function &_get($var){
		if(isset(self::$_sys_vars[$var])){
			return self::$_sys_vars[$var];
		}
		return NULL;
	}
	
	public static function _del($var){
		if(isset(self::$_sys_vars[$var])){
			unset(self::$_sys_vars[$var]);
		}		
	}
	
	public static function _clear($var){
		if(isset(self::$_sys_vars[$var])){
			self::$_sys_vars[$var] = NULL;	
		}	
	}
	
	public static function _reset($var){
		self::$_sys_vars = array();		
	}
}

/***全局变量注册类应用示例
$a1 = "这是第一条";
$a2 = "这是第二条";
$arr = array();
$arr[] = "11";
$arr[] = "22";

register::_set("_layout_parms1",$a1);
register::_set("_layout_parms2",$a2);
register::_set("abc","123"); //注意会出错，因为"123"是字符串常量，不是变量;
$abc = register::_get('_layout_parms1');
register::_set("arr",$arr);
$arr = register::_get("arr");
var_dump($arr);
*/
?>