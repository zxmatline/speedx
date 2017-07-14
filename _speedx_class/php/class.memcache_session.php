<?php
	class memsession {
		const NS = 'memsession_';                   //声明一个memcached键前缀，防止冲突
		protected static $mem = null;               //声明一个处理器，使用memcached处理Session信息
		protected static $lifetime = null;          //声明Session的生存周期
		protected static $ip = null; 				//客户端IP地址;	
		protected static $agent = null;				//用户代理浏览器;
		
		
		public static function start(Memcache $mem) {
			self::$mem = $mem;
			self::$lifetime = ini_get('session.gc_maxlifetime');
			self::$agent = !empty($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'unknow';//获取用户代理浏览器信息；

			self::$ip = empty($_SERVER['REMOTE_ADDR'])?'unknown':$_SERVER['REMOTE_ADDR'];//获取当前浏览页的客户IP					
			self::$ip = filter_var(self::$ip,FILTER_VALIDATE_IP)?self::$ip : 'unknown'; //检验IP地址的有效性
			self::$lifetime = ini_get('session.gc_maxlifetime'); //获取session对话的最大失效时间;也可以直接赋值，如：1800;		
			session_set_save_handler(
					array(__CLASS__, 'open'),
					array(__CLASS__, 'close'),
					array(__CLASS__, 'read'),
					array(__CLASS__, 'write'),
					array(__CLASS__, 'destroy'),
					array(__CLASS__, 'gc')
			);
			session_start();
		}

		private static function open($path, $name) {
			return true;
		}

		public static function close() {
			return true;
		}

		private static function read($sid) {
			$new_sid = self::session_key($sid);
			$ip_key = $new_sid."_ip";
			$agent_key = $new_sid."_agent";
			
			$ip_value = self::$mem->get($ip_key);
			$agent_value = self::$mem->get($agent_key);
			$data = self::$mem->get($new_sid);
			
			if (empty($ip_key) || empty( $agent_key) || empty($data) || $ip_value !== self::$ip || $agent_value !== self::$agent) {return '';}
			
			return $data;
		}

		public static function write($sid, $data) {
			$method = $data ? 'set' : 'replace';
			$new_sid = self::session_key($sid);
			$ip_key = $new_sid."_ip";
			$agent_key = $new_sid."_agent";
			self::$mem->$method($new_sid, $data, MEMCACHE_COMPRESSED, self::$lifetime);
			self::$mem->$method($ip_key,self::$ip,MEMCACHE_COMPRESSED, self::$lifetime);
			self::$mem->$method($agent_key,self::$agent,MEMCACHE_COMPRESSED, self::$lifetime);
			return true;
		}

		public static function destroy($sid) {
			$new_sid = self::session_key($sid);
			$ip_key = $new_sid."_ip";
			$agent_key = $new_sid."_agent";
			
			self::$mem->delete($new_sid);
			self::$mem->delete($ip_key);
			self::$mem->delete($agent_key);
			return true;
		}

		private static function gc($lifetime) {
			return true;
		}

		
		private static function session_key($sid) {
			$session_key = '';
			/*
			if (defined('PROJECT_NS')) {
				$session_key .= PROJECT_NS;
			}
			*/
			$session_key .= self::NS . $sid;

			return $session_key;
		}
	}


