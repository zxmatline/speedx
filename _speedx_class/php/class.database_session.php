<?php
class dbsession{	
	protected static $ip = null; //客户端IP地址;	
	protected static $agent = null;//用户代理浏览器;
	protected static $time = null;//当前时间;	
	protected static $pdo = null; //数据库连接句柄;
	protected static $lifetime = null;//session生命周期;
	
	
	public static function start(PDO $pdo){
		self::$pdo = $pdo;
		self::$agent = !empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'unknow';//获取用户代理浏览器信息；		
		self::$ip = empty($_SERVER['REMOTE_ADDR'])?'unknown':$_SERVER['REMOTE_ADDR'];//获取当前浏览页的客户IP					
		self::$ip = filter_var(self::$ip,FILTER_VALIDATE_IP)?self::$ip : 'unknown'; //检验IP地址的有效性
		
		self::$lifetime = ini_get('session.gc_maxlifetime'); //获取session对话的最大失效时间;也可以直接赋值，如：1800;		
		self::$time = time(); //当前时间;		
		session_set_save_handler(
			array(__CLASS__,'open'),
			array(__CLASS__,'close'),
			array(__CLASS__,'read'),
			array(__CLASS__,'write'),    
			array(__CLASS__,'destroy'), 
			array(__CLASS__,'gc')
		);		
		session_start();		
	}		
	
	public static function open($sPath,$sName){ return true; }
	
	public static function close(){ return true; }
	
	
	public static function read($sid){
		$query = "select * from dbsession where sid = :sessionID";
		$stmt = self::$pdo->prepare($query);
		$stmt->bindParam(':sessionID',$sid);
		$stmt->execute();		
		if($stmt->rowCount() > 0){
			$result = $stmt->fetch(PDO::FETCH_ASSOC);			
			if(self::$ip != $result['clientIP'] || self::$time > $result['lastTime'] + self::$lifetime){				
				self::destroy($sid);				
				return '';
			}	
			return $result['sessionData'];
		}else{
			return '';
		}		
	}		
	
	public static function write($sid, $data) {
		$query = 'select * from dbsession where sid = ?';
		$stmt = self::$pdo->prepare($query);
		$stmt->execute(array($sid));		
		
		if($stmt->rowCount() > 0){
			$update = "update dbsession set clientIP = ?,lastTime = ?,sessionData = ?,agent = ?, where sid = ?";
			$stmt = self::$pdo->prepare($update);
			$stmt->execute(array(self::$ip,self::$time,$data,self::$agent,$sid));
			
		}else{			
			$insert = "insert into dbsession(sid,clientIP,lastTtime,sessionData,agent) values (:sid,:ip,:time,:data,:agent)";
			$stmt = self::$pdo->prepare($insert);			
			$stmt->execute(array(":sid" => $sid,":ip" => self::$ip, ":time" => self::$time, ":data" => $data,":agent" => self::$agent));					
		}
		return true;
	}
	
	public static function destroy($sid){		
		$del = "delete from dbsession where sid = :sid";
		$stmt = self::$pdo->prepare($del);	
		$stmt->execute(array(":sid" => $sid));
		return true;
	}
	
	public static function gc($sTime){
		$temTime = self::$time - self::$lifetime;
		//echo $temTime;
		$gc = "delete from dbsession where lastTime < :temTime";
		$stmt = self::$pdo->prepare($gc);
		$stmt->execute(array(":temTime" => $temTime));
		return true;		
	}	
}
?>
