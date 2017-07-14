<?php
/***数据库session方式
include SYSDATABASE."link_to_db_session.php";
include SYSDATABASE."class.database_session.php";
$pdo = dblinker::linkToDB();
dbsession::start($pdo);

*/

/**memcached处理方式*/
	include(SYSDATABASE."class.memcache_session.php");
	$mem = new Memcache;
	/* 添加memcached的服务器，可以添加多个做分布式 */
	$mem -> addServer("localhost", 11211);	
	memsession::start($mem);
?>