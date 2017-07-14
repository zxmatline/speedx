<?php
class dblinker{
	protected static $dbname = "xintengdatabase";
	protected static $dbuser = "xinteng";
	protected static $dbkey = "xintengdblink";
	
	public function __set($varName,$value){
		self::$varName = $value;
	}
	
	public static  function linkToDB(){
		try{
			$dbh = new PDO('mysql:host=localhost;dbname='.self::$dbname,self::$dbuser,self::$dbkey);
		}catch(PDOException $e){
			echo "数据库连接失败：" .$e->getMessage();
			exit;
		}		
		return $dbh;
	}	
}

?>
