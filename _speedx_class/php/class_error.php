<?php
// error_reporting(0); 

/***
*******************************************************************
****************   自定义错误类--用户主动抛出错误  ****************
****	在try-catch块中抛出错误，本自定义类要实例化才能使用：如
****	throw new speedx_err_exception('无效的参数', 5); 
*******************************************************************
*/
class speedx_err_exception extends Exception  
{  
    // 重定义构造器使 message 变为必须被指定的属性  
    public function __construct($message, $code = 0) {  
        // 自定义的代码  
  
        // 确保所有变量都被正确赋值  
        parent::__construct($message, $code);  
    }  
  
    // 自定义字符串输出的样式 */  
    public function __toString() {  
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";  
    }  
  
    public function customFunction() {  
        echo "A Custom function for this type of exception\n";  
    }  
} 

/***
*******************************************************************
****************   自定义错误信息--用户级错误  ****************
****	以 set_error_handler设置，一般用于捕捉：
****	E_NOTICE 、E_USER_ERROR、E_USER_WARNING、E_USER_NOTICE
****	不能捕捉：
****	E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, 
****	E_COMPILE_ERROR and E_COMPILE_WARNING。
****	一般与trigger_error("...", E_USER_ERROR)，配合使用。
*******************************************************************
*/
function speedx_err_handler($errno, $errmsg, $filename, $linenum, $vars)  
{  
    // timestamp for the error entry      
    $dt = date("Y-m-d H:i:s (T)");      
    // define an assoc array of error string      
    // in reality the only entries we should      
    // consider are E_WARNING, E_NOTICE, E_USER_ERROR     
    // E_USER_WARNING and E_USER_NOTICE      
    $errortype = array (                  
        E_ERROR              => 'Error',                  
        E_WARNING            => 'Warning',                  
        E_PARSE              => 'Parsing Error',                  
        E_NOTICE             => 'Notice',                  
        E_CORE_ERROR         => 'Core Error',                  
        E_CORE_WARNING       => 'Core Warning',                  
        E_COMPILE_ERROR      => 'Compile Error',                  
        E_COMPILE_WARNING    => 'Compile Warning',                  
        E_USER_ERROR         => 'User Error',                  
        E_USER_WARNING       => 'User Warning',                  
        E_USER_NOTICE        => 'User Notice',                  
        E_STRICT             => 'Runtime Notice',                  
        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'                  
    );      
    // set of errors for which a var trace will be saved      
    $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);          
    $err = "<errorentry>\n";      
    $err .= "\t<datetime>" . $dt . "</datetime>\n";      
    $err .= "\t<errornum>" . $errno . "</errornum>\n";      
    $err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";      
    $err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";      
    $err .= "\t<scriptname>" . $filename . "</scriptname>\n";      
    $err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";      
    if (in_array($errno, $user_errors)) {          
        $err .= "\t<vartrace>" . wddx_serialize_value($vars, "Variables") . "</vartrace>\n";      
    }      
    $err .= "</errorentry>\n\n";  
    echo $err;  
}  
  
//自定义触发用户级错误句柄：
//$speedx_old_err_handler = set_error_handler("speedx_err_handler");

// 触发错误示例：
//trigger_error("这儿是用户自定义错误提示信息", E_USER_ERROR); 

/***
*******************************************************************
****************   自定义错误信息--错误默认处理方式  ****************
****	set_exception_handler ：
****	设置默认的异常处理程序，用于没有用 try/catch 块来捕获的异常。 
****	在 exception_handler 调用后异常会中止。 
****	与throw new Exception('Uncaught Exception occurred')，连用。
*******************************************************************
*/
function speedx_err_uncaught_exceptHandler($errno, $errmsg, $filename, $linenum, $vars)  
{  
    $dt = date("Y-m-d H:i:s (T)");      
    // define an assoc array of error string      
    // in reality the only entries we should      
    // consider are E_WARNING, E_NOTICE, E_USER_ERROR,      
    // E_USER_WARNING and E_USER_NOTICE      
    $errortype = array (                  
        E_ERROR              => '致命错误',                  
        E_WARNING            => '警告',                  
        E_PARSE              => '语法错误',                  
        E_NOTICE             => '注意',                  
        E_CORE_ERROR         => 'Core Error',                  
        E_CORE_WARNING       => 'Core Warning',                  
        E_COMPILE_ERROR      => '编译错误',                  
        E_COMPILE_WARNING    => '编译警告',                  
        E_USER_ERROR         => '用户级别错误',                  
        E_USER_WARNING       => '用户级别警告',                  
        E_USER_NOTICE        => '用户级别注意',                  
        E_STRICT             => '运行时注意',                  
        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error'                  
    );      
    // set of errors for which a var trace will be saved      
    $err = "<errorentry>\n";      
    $err .= "\t<datetime>" . $dt . "</datetime>\n";      
    $err .= "\t<errornum>" . $errno . "</errornum>\n";      
    $err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";      
    $err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";      
    $err .= "\t<scriptname>" . $filename . "</scriptname>\n";      
    $err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";      
    if (1) {          
        $err .= "\t<vartrace>" . wddx_serialize_value($vars, "Variables") . "</vartrace>\n";      
    }      
    $err .= "</errorentry>\n\n";  
    echo $err;  
} 
//自定义触发用户级错误句柄：
//$old_except_handle = set_exception_handler("speedx_err_uncaught_exceptHandler");
//抛出错误，此错误将由自定义的句柄捕获
//throw new Exception('Uncaught Exception occurred');   

/***
*******************************************************************
****************   自定义错误——页面崩溃处理  ****************
***	register_shutdown_function('自定义异常类'):
***	这个函数的作用就是在退出脚本前，调用已经注册的函数，并执行该函数。
*******************************************************************
*/
//error_reporting(0);  
//date_default_timezone_set('Asia/Shanghai');  
//register_shutdown_function('my_exception_handler');  
  
    
function my_exception_handler()  
{  
    if($e = error_get_last()) {  
    //$e['type']对应php_error常量  
    $message = '';  
    $message .= "出错信息:\t".$e['message']."\n\n";  
    $message .= "出错文件:\t".$e['file']."\n\n";  
    $message .= "出错行数:\t".$e['line']."\n\n";  
    $message .= "\t\t请工程师检查出现程序".$e['file']."出现错误的原因\n";  
    $message .= "\t\t希望能您早点解决故障出现的原因<br/>";  
    echo $message;  
    //sendemail to  
    }  
}  



/***
*******************************************************************
****************   错误信息样式类  ****************
*******************************************************************
*/

class speedx_err_style{
	private $err_no = 0;
	private $err_msg = "";	
}

/***
*******************************************************************
****************   错误文件获取  ****************
*******************************************************************
*/

class speedx_get_error_content{
	
}



?>