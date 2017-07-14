<?php
/////////////////////////////////////////////////////////////////////
////////////////////////********开始*********/////////////////////////
////////////////////////******表单验证类*******//////////////////////////

/********************************************************************/
class veriForms{	
	private $db = null;	
	private $message = "";	
	function __construct($db){
		$this->db = $db;
	}
	
	public function pregVal($type,$value){
		$reg_name = "/^\w{4,30}$/";
		$reg_shouji = "/^\d{11}$/";
		$reg_email = "/^\w{1,20}(?:@(?!-))(?:(?:[a-z0-9-]*)(?:[a-z0-9](?!-))(?:\.(?!-)))+[a-z]{2,4}$/i";		
		$reg_date =  "/^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/";
		$reg_datetime = "/^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}\s+[0-2]?[0-4]{1}:[0-5]?[0-9]{1}:[0-5]?[0-9]{1}$/";
		$reg_password = "/^[\w#\$%\*@]{6,30}$/";
		$reg_alias = "/^[^\s\.\\\<\>\d]{2,20}$/i";
		$reg_checkCode = "/^[0-9a-zZ-Z]{4}$/"; 
		$reg_sendCode = "/^[0-9]{6}$/"; 
		$reg_sex = "/^[bgn]{1}$/i"; 
		
		$returnPreg = false;	
		
		switch($type){
		case "alpha": //字母名称
			$returnPreg = preg_match($reg_name,$value); 
			break;
		case "alias": //别名	
			$returnPreg = preg_match($reg_alias,$value);	
			break;
		case "alpha_phone_email": //字母名称+手机名称+邮箱名称
			$returnPreg = preg_match($reg_name,$value) || preg_match($reg_shouji,$value) || preg_match($reg_email,$value);
			break;
		case "phone": //手机名称
			$returnPreg = preg_match($reg_shouji,$value);
			break;
		case "email"://邮箱名称
			$returnPreg = preg_match($reg_email,$value);
			break;
		case "phone_email"://手机名称+邮箱名称
			$returnPreg = preg_match($reg_shouji,$value) || preg_match($reg_email,$value);
			break;
		case "password"://密码
			$returnPreg = preg_match($reg_password,$value);
			break;
		case "sex": //性别
			$returnPreg = preg_match($reg_sex,$value);
			break;
		case "date": //日期
			$returnPreg = preg_match($reg_date,$value);
			break;
		case "datetime": //日期时间
			$returnPreg = preg_match($reg_datetime,$value);
			break;
		case "imgCode": //图片验证码
			$returnPreg = preg_match($reg_checkCode,$value);
			break;
		case "imgCode_emailCode": //手机或邮箱验证码
			$returnPreg = preg_match($reg_sendCode,$value);
			break;
		}
		return $returnPreg;
	}
	
	public function findUser($name){
		$name = trim($name);
		$query = "select user_ID from db_user_Main where user_ID = ?";
		$stmt = $this->db->prepare($query);
		$stmt->execute(array($name));
		if($stmt->rowCount() > 0){			
			return true;
		}else{
			return false;
		}
	}
	
	private function findPhone($Phone){
		$PE = trim($Phone);
		$query = "select user_ID from db_user_Main where user_hset = ?";
		$stmt = $this->db->prepare($query);
		$stmt->execute(array($Phone));
		if($stmt->rowCount() > 0){			
			return true;
		}else{
			return false;
		}
	}
	
	private function findEmail($Email){
		$PE = trim($Email);
		$query = "select user_ID from db_user_Main where user_email = ?";
		$stmt = $db->prepare($query);
		$stmt->execute(array($Email));
		if($stmt->rowCount() > 0){			
			return true;
		}else{
			return false;
		}
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	public function createRandCode($length = 6){//生成随机代码	
		 $key = "";
		 $chars='abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
		 //$chars='0123456789';
		 for($i=0;$i<$length;$i++)
		 {
		   $key .= $chars[mt_rand(0,strlen($chars)-1)];    //生成php随机数
		 }
		 return $key;	
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	private function codeMax($value){ //检查发送邮件和短信的发送条件：数量上限，时效性
		$value = trim($value);		
		$now = time();		
		$ie = !empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'unknow';//获取用户代理浏览器；
		$ie = substr($ie,0,100);		
		$ip = empty($_SERVER['REMOTE_ADDR'])?'unknown':$_SERVER['REMOTE_ADDR'];//获取当前浏览页的客户IP					
		$ip = filter_var($ip,FILTER_VALIDATE_IP)?$ip : 'unknown'; //检验IP地址的有效性
		
		$getdate = getdate($now);			 
		$today = mktime(0,0,0,$getdate['mon'],$getdate['mday'],$getdate['year']);		
		$query = "delete from db_user_register where nDateTime < ? ";			  
		$stmt = $this->db->prepare($query);
		$stmt->execute(array($today));		
					
		$query = "select * from db_user_register where userAcount = ?";
		$stmt = $this->db->prepare($query);
		$stmt->execute(array($value));		
		  
		if($stmt->rowCount() > 0){			  
		   $result = $stmt->fetch(PDO::FETCH_ASSOC);		 
		   if($result['codeCount'] >= 5){
			  $this->message = "该帐户验证码发送的数量已经超额，请明天再试!";
			  return false;						
		   }	
		   if($now - $result['nDateTime'] <= 100){
			   $lostTime = 100 - ($now - $result['nDateTime']);					
			    $this->message = "验证码发送过于频繁：".$lostTime . "秒后才能继续发送！";
			   return false;											
		   }		  
		}else{
		   $query = "insert into db_user_register(userAcount,codeCount,userIE,userIP,nDateTime)values(?,?,?,?,?)";
		   $stmt = $this->db->prepare($query);				
		   $stmt->execute(array($value,0,$ie,$ip,$now));		  
		}
		$this->message = "";
		return true;
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	private function updateSendCodeDB($value,$code,$type){//更新发送验证码数据库信息
		$now = time();		
		$ie = !empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'unknow';//获取用户代理浏览器；
		$ie = substr($ie,0,100);		
		$ip = empty($_SERVER['REMOTE_ADDR'])?'unknown':$_SERVER['REMOTE_ADDR'];//获取当前浏览页的客户IP					
		$ip = filter_var($ip,FILTER_VALIDATE_IP)?$ip : 'unknown'; //检验IP地址的有效性
			
		$query = "update db_user_register set veriCode = ?,type = ?, codeCount = codeCount+1,userIE = ?,userIP = ?,nDateTime = ?  where userAcount = ?";
		$stmt = $this->db->prepare($query);
		$stmt->execute(array($code,$type,$ie,$ip,$now,$value));	
	}
/**************************发送邮件验证码******************************/
	private function sendEmail($code,$userEmail){
		header("content-type:text/html;charset=utf-8"); 
		ini_set("magic_quotes_runtime",0);		 
		require_once("class.phpmailer.php");		
		$mail = new PHPMailer(); 
		$mail->isSMTP();
		$mail->SMTPAuth=true;
		$mail->Host = 'smtp.qq.com';
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;
		$mail->Helo = 'www.xiaomingzx.com';
		$mail->Hostname = 'www.xiaomingzx.com';
		$mail->CharSet = 'UTF-8';
		$mail->FromName = '小明在线';
		$mail->Username ='xiaomingzx_2015';
		$mail->Password = 'xiaomingzx.com';
		$mail->From = 'xiaomingzx_2015@qq.com';
		$mail->isHTML(true); 
		$mail->addAddress($userEmail,'小明在线用户');
		$mail->Subject = '小明在线邮箱注册验证码';
		$mail->Body = "尊敬的用户，您好<br/>您在【小明在线】网的邮箱验证码为：<b style='color:red;'>".$code."，</b>请在10分钟内验证！";
		$status = $mail->send();
		return $status;		 
	}
/**************************发送短信验证码******************************/
	private function sendSMS($code,$userPhone){
		$data ="尊敬的用户，你在【小明在线】网上的短信验证码是：" . $code .",请在10分钟内验证!" ;
		$post_data = array();
		$post_data['account'] = iconv('GB2312', 'GB2312',"jiekou-clcs-04");
		$post_data['pswd'] = iconv('GB2312', 'GB2312',"Tch147369");
		$post_data['mobile'] = $userPhone;
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
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	public function ifSendEmail($email,$code,$requestFile){
		if(!$this->pregVal("email",$email)){
			$this->message = "邮箱格式不符合要求！";
			return false;
		}		
		if(!$this->codeMax($email)){return false;}		 
		
		$result =$this->sendEmail($code,$email);
		if(!$result){		
		  $this->message = "邮箱验证码发送失败，请稍后重试！";
		  return false;					 
		}		
	
		$this->updateSendCodeDB($email,$code,$requestFile);
		$this->message = "";
		return true;	 		   
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	public function ifSendPhone($phone,$code,$requestFile){
		if(!$this->pregVal("phone",$phone)){
			$this->message = "手机格式不符合要求！";
			return false;
		}		
		
		if(!$this->codeMax($phone)){return false;}
		
		$result =$this->sendSMS($code,$phone);
		if(!$result){		
		  $this->message = "手机验证码发送失败，请稍后重试！";
		  return false;					 
		}
		
		$this->updateSendCodeDB($phone,$code,$requestFile);
		$this->message = "";
		return true;	 		   
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	public function veriImgSessionCode($value){	
	   if(!isset($_SESSION['imgCode'])){$this->message = "请刷新图片验证码！";return false;}	 
	   if($value == $_SESSION['imgCode']){
		  if(time()- $_SESSION['imgCreateTime'] > 60*5){$this->message = "图片验证码已过期！";return false;}							 
		}else{				
			$this->message = "图片验证码不正确!!";
			return false;					 
		}	
		$this->message = "";
		return true;		
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	public function getErr(){
		return $this->message;
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	public function veriPhoneSessionCode($value,$phone){
		 if(!($this->pregVal("phone",$phone))){$this->message = "手机号不符合定义";return false;}
		 if(!(isset($_SESSION['phoneCode']))){$this->message = "还没有发送验证码";return false;}
		 $session = explode("|",$_SESSION['phoneCode']);		 
		 if($phone !== $session[0] || $value !== $session[1]){$this->message = "验证码错误";return false;}
		 if(time()- $_SESSION['phoneCreateTime'] > 60*10){$this->message = "短信验证码已过期";return false;} 
		 $this->message = ""; 		
		 return true;
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	public function veriEmailSessionCode($value,$email){
		 $value = strtoupper($value);
		 $email = strtoupper($email);
		 if(!($this->pregVal("email",$email))){$this->message = $email;return false;}
		 if(!(isset($_SESSION['emailCode']))){$this->message = "还没有发送验证码";return false;}
		 $_SESSION['emailCode'] = strtoupper($_SESSION['emailCode']);
		 $session = explode("|",$_SESSION['emailCode']);
		 if($email !== $session[0] || $value !== $session[1]){$this->message = "验证码错误";return false;}
		 if(time() - $_SESSION['emailCreateTime'] > 60*10){$this->message = "短信验证码已过期";return false;} 
		 $this->message = ""; 		
		 return true;	
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
}

	
?>