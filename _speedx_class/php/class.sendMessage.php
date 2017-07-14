<?php
class sendMessage{
	private $fromHostname = "www.xiaomingzx.com";	
	private $helo = "www.xiaomingzx.com";
	private $userName = "www.xiaomingzx.com";
	private $fromName = "小明在线网";
	private $userName = "xiaomingzx_2015";
	private $password = "xiaomingzx.com";
	private $fromEmail = "xiaomingzx_2015@qq.com";
	private $fromAlias = "小明在线";//发件者邮箱的昵称
	private $toEmail = "";
	private $attachFiles = array(
							array("dir"=>"./","file"=>"abc1.jpg"),
							array("dir"=>"./","file"=>"abc2.jpg"),
							array("dir"=>"./","file"=>"abc3.jpg")
							);//附件文件
	
	
	private $userPhone = "";
	
	
	private $smgHeader = "小明在线网邮箱验证码";
	private $smgBody = "";
	
	public function sendEmail($code = ""){
		header("content-type:text/html;charset=utf-8"); 
		ini_set("magic_quotes_runtime",0);		 
		require_once("class.phpmailer.php");		
		$mail = new PHPMailer(); 
		//是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
		//$mail->SMTPDebug = 1; 
		//使用smtp鉴权方式发送邮件，当然你可以选择pop方式 sendmail方式等 本文不做详解
		//可以参考http://phpmailer.github.io/PHPMailer/当中的详细介绍
		$mail->isSMTP();
		//smtp需要鉴权 这个必须是true
		$mail->SMTPAuth=true;
		//链接qq域名邮箱的服务器地址
		$mail->Host = 'smtp.qq.com';
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
		$mail->Helo = $this->helo;
		//设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
		$mail->Hostname = $this->fromHostname;
		//设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
		$mail->CharSet = 'UTF-8';
		$mail->FromName = $this->fromName;
		$mail->Username = $this->userName;
		$mail->Password = $this->password;
		$mail->From = $this->fromEmail;
		//邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
		$mail->isHTML(true); 
		//设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
		$mail->addAddress($userEmail,$this->fromAlias);
		//添加多个收件人 则多次调用方法即可
		//$mail->addAddress('zxmatline@163.com','小明在线用户');
		//添加该邮件的主题
		$mail->Subject = $this->smgHeader;
		//添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
		$mail->Body = $this->smgBody;
		//为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
		//$mail->addAttachment('./d.jpg','mm.jpg');
		//同样该方法可以多次调用 上传多个附件
		//$mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');
		 
		 if($this->attachFiles !== ""){
		 	foreach($this->attachFiles as $value){
				$mail->AddAttachment($value["dir"],$value["file"]);		
			}
		 }
		 
		//发送命令 返回布尔值 
		//PS：经过测试，要是收件人不存在，若不出现错误依然返回true 也就是说在发送之前 自己需要些方法实现检测该邮箱是否真实有效
		$status = $mail->send();
		return $status;		 
		//简单的判断与提示信息
		/*if($status) {
		 echo '发送邮件成功';
		}else{
		 echo '发送邮件失败，错误信息：'.$mail->ErrorInfo;
		}*/
	}
	public function sendSMS(){
		$data = $this->smgBody;
		//$_SESSION['smsCode'] = $code;
		//$_SESSION['smsPhone'] = $userPhone;	
		 //发送短信			 
		$post_data = array();
		$post_data['account'] = iconv('GB2312', 'GB2312',"jiekou-clcs-04");
		$post_data['pswd'] = iconv('GB2312', 'GB2312',"Tch147369");
		$post_data['mobile'] = $this->userPhone;
		$post_data['msg'] = mb_convert_encoding("$data",'UTF-8', 'auto');
		$url='http://222.73.117.158/msg/HttpBatchSendSM?'; 
		$o="";
		foreach ($post_data as $k=>$v)
		{
		   $o.= "$k=".urlencode($v)."&";
		}
		$post_data=substr($o,0,-1);		   

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result = curl_exec($ch);
		$result = (intval(substr($result,strrpos($result,',')+1)) == 0) ? true : false;
		return $result;	
	}
	
	public function createRandCode($length){
		$charset = "abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789";
		$outCodeSet = "";
		$charLen = strlen($charset) - 1;
		for($var = 0; $var < $length; $var++){
			$outCodeSet .= $charset[mt_rand(0,$charLen)];
		}
		return $outCodeSet;	
	}
	
	public function __set($varName,$varValue){
		$this->$varName = $varValue;
	}
}
?>