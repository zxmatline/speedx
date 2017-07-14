<?php
error_reporting(0);//所有的错误都不用系统定定义的 
//ini_set('display_errors', 'On');  
//error_reporting(E_ALL & ~ E_WARNING); 
//set error handler
//set_error_handler("customError");
//trigger error
//set_exception_handler("exceptHandle");


/*开启php.ini中的display_errors指令，只有该指令开启如果有错误报告才能输出*/

	//ini_set('display_errors',1); 

	/*通过error_reporting()函数设置在本脚本中，输出所有级别的错误报告*/

	//error_reporting(E_ALL);
	//error_reporting(E_ALL & ~(E_WARNING | E_NOTICE));
	//error_reporting(E_ALL);

	/*“注意(notice)”的报告，不会阻止脚本的执行，并且可能不一定是一个问题 */

 function exceptionHandler(){
        error_reporting(E_ALL);
        date_default_timezone_set('Etc/GMT-8');    //设置时区
   
        ini_set('display_errors',0);    //将错误记录到日志
        ini_set('error_log','D:\\'.date('Y-m-d').'_weblog.log');
        ini_set('log_errors',1);    //开启错误日志记录
        ini_set('ignore_repeated_errors',1);    //不重复记录出现在同一个文件中的同一行代码上的错误信息。

        $user_defined_err = error_get_last();
        if($user_defined_err['type'] > 0){        
            switch($user_defined_err['type']){
                case 1:
                    $user_defined_errType = '致命的运行时错误(E_ERROR)';
                    break;
                case 2:
                    $user_defined_errType = '非致命的运行时错误(E_WARNING)';
                    break;
                case 4:
                    $user_defined_errType = '编译时语法解析错误(E_PARSE)';
                    break;
                case 8:
                    $user_defined_errType = '运行时提示(E_NOTICE)';
                    break;
                case 16:
                    $user_defined_errType = 'PHP内部错误(E_CORE_ERROR)';
                    break;
                case 32:
                    $user_defined_errType = 'PHP内部警告(E_CORE_WARNING)';
                    break;
                case 64:
                    $user_defined_errType = 'Zend脚本引擎内部错误(E_COMPILE_ERROR)';
                    break;
                case 128:
                    $user_defined_errType = 'Zend脚本引擎内部警告(E_COMPILE_WARNING)';
                    break;
                case 256:
                    $user_defined_errType = '用户自定义错误(E_USER_ERROR)';
                    break;
                case 512:
                    $user_defined_errType = '用户自定义警告(E_USER_WARNING)';
                    break;
                case 1024:
                    $user_defined_errType = '用户自定义提示(E_USER_NOTICE)';
                    break;
                case 2048:
                    $user_defined_errType = '代码提示(E_STRICT)';
                    break;
                case 4096:
                    $user_defined_errType = '可以捕获的致命错误(E_RECOVERABLE_ERROR)';
                    break;
                case 8191:
                    $user_defined_errType = '所有错误警告(E_ALL)';
                    break;
                default:
                    $user_defined_errType = '未知类型';
                    break;
                }
            $msg = sprintf('%s %s %s %s %s',date("Y-m-d H:i:s"),$user_defined_errType,$user_defined_err['message'],$user_defined_err['file'],$user_defined_err['line']);
            error_log($msg,0);
        }
    }

    register_shutdown_function('exceptionHandler');

	getType($var);             //调用函数时提供的参数变量没有在之前声明

	/*“警告(warning)”的报告，指示一个问题，但是不会阻止脚本的执行 */

	getType();                 //调用函数时没有提供必要的参数

	/*“错误(error)”的报告，它会终止程序，脚本不会再向下执行 */

	//get_Type();                //调用一个没有被定义的函数
?>