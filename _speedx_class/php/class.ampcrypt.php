<?php
/*信息的加密与解密算法，基于php的扩展mcrypt*/
class ampcrypt {
     private static function getKey($salt = '$1$www.xiaomingzx.com'){
         return md5($salt);
      }
	  
     public static function encrypt($value,$salt){
          $td = mcrypt_module_open('tripledes', '', 'ecb', '');
          $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
          $key = substr(self::getKey($salt), 0, mcrypt_enc_get_key_size($td));
          mcrypt_generic_init($td, $key, $iv);
          $ret = base64_encode(mcrypt_generic($td, $value));
          mcrypt_generic_deinit($td);
          mcrypt_module_close($td);
         return $ret;
      }
	  
     public static function dencrypt($value,$salt){
          $td = mcrypt_module_open('tripledes', '', 'ecb', '');
          $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
          $key = substr(self::getKey($salt), 0, mcrypt_enc_get_key_size($td));
          $key = substr(self::getKey($salt), 0, mcrypt_enc_get_key_size($td));
          mcrypt_generic_init($td, $key, $iv);
          $ret = trim(mdecrypt_generic($td, base64_decode($value))) ;
          mcrypt_generic_deinit($td);
          mcrypt_module_close($td);
         return $ret;
      }
 }
?>