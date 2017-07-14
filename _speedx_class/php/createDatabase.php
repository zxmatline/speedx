<?php
	include "linktodb.php";
	//include "dbsession.php";
	$dbh = dblinker::linkToDB();
	/**
	*创建dbsession数据库,注意，mysql中的函数与php中的函数不同，不能混用，如，mysql中的now()函数在php中是无效的，php中对应的是time()函数；
	*/
	$query = "create table if not exists dbsession(sid varchar(100) not null,clientIP char(15) not null default '0.0.0.0',lastTime datetime not null default  now(),userAgent text not null default '',sessionData text not null default '')";
	$dbh->exec($query);
	
	new pdo
	/**
	*创建session数据库
	
	$query = "create table if not exists db_user_register(user_id varchar(32) not null default '',veriCode char(6) not null default '000000',type  varchar(15) not null default '000000',codeCount int not null default 0,userIE varchar(120) not null default '',userIP varchar(15) not null default '',nDateTime int not null default 0)";
$dbh->exec($query);
*/
?>