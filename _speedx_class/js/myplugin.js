jQuery.myplugin = {
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	pregValue:function($veriType,$ele,$options){//================================================================================ 
		var $cValue = $.trim($ele.val());
		var	defaultParms = {
			reg_name:/^\w{4,30}$/,
			reg_shouji:/^\d{11}$/,
			reg_email:/^\w{1,20}(?:@(?!-))(?:(?:[a-z0-9-]*)(?:[a-z0-9](?!-))(?:\.(?!-)))+[a-z]{2,4}$/,		
			reg_date: /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/,
			reg_datetime:/^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}\s+[0-2]?[0-4]{1}:[0-5]?[0-9]{1}:[0-5]?[0-9]{1}$/,
			reg_password:/^[\w#\$%\*@]{6,30}$/,
			reg_alias:/^[^\s\.\\\<\>\d]{2,60}$/i,
			reg_imgCode:/^[0-9a-zA-Z]{4}$/,
			reg_sendCode:/^[0-9a-zA-Z]{6}$/i, 
			reg_sex:/^[bgn]{1}$/i
		}
		
		var opts = $.extend({},defaultParms,$options);
		if($cValue == ""){return false;}		
		var $returnPreg = false;	

		switch($veriType){
		case "alpha": //字母名称
			$cValue = $cValue.replace(/\s/g,'');			
			$returnPreg = opts.reg_name.test($cValue);			
			break;
		case "alias": //别名
			$cValue = $cValue.replace(/\s/g,'');	
			$returnPreg = opts.reg_alias.test($cValue);	
			break;
		case "alpha_phone_email": //字母名称+手机名称+邮箱名称
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_name.test($cValue) || opts.reg_shouji.test($cValue) || opts.reg_email.test($cValue);
			break;
		case "phone": //手机名称
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_shouji.test($cValue);
			break;
		case "email"://邮箱名称
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_email.test($cValue);
			break;
		case "phone_email"://手机名称+邮箱名称
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_shouji.test($cValue) || opts.reg_email.test($cValue);
			break;
		case "password"://密码
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_password.test($cValue);
			break;
		case "sex": //性别
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_sex.test($cValue);
			break;
		case "date": //日期
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_date.test($cValue);
			break;
		case "dateTime": //日期时间
			$returnPreg = opts.reg_datetime.test($cValue);
			break;
		case "imgCode": //图片验证码
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_imgCode.test($cValue);
			break;
		case "sendPhoneCode": //手机或邮箱验证码
		case "sendEmailCode": //手机或邮箱验证码
			$cValue = $cValue.replace(/\s/g,'');
			$returnPreg = opts.reg_sendCode.test($cValue);
			break;
		}
		return $returnPreg;
	},	
	
	bindBlur:function($ele,$veriType,$options){
		var defaultParms = {
				position:2,
				emptytip:"没有输入值",
				errtip:"不符合要求"
			};
		var opts = $.extend({},defaultParms,$options);
		if(opts.position < 1 || opts.position > 4){opts.position = 2}		
		var $value = $.trim($ele.val());
		if($value == ""){layer.tips(opts.emptytip, $ele, {tips:opts.position});return false;}		
		if(!$.myplugin.pregValue($veriType,$ele)){
			layer.tips(opts.errtip, $ele, {tips: [opts.position,'red']});
			$ele.select();
			return false;
		}
	},
	rePwdBlur:function($pwdele,$ele,$veritype,$options){
		var defaultParms = {
				position:2,
				emptytip:"不能为空",
				errtip:"二次密码输入不一致"
			};
		var opts = $.extend({},defaultParms,$options);
		if(opts.position < 1 || opts.position > 4){opts.position = 2;}
		
		var $pwdVal = $.trim($pwdele.val());
		if($pwdVal == ""){
			$pwdele.focus();
			layer.tips("密码不能为空", $pwdele, {tips:opts.position});
			return false;
		};
		
		var $value = $.trim($ele.val());
		if($value == ""){layer.tips(opts.emptytip, $ele, {tips:opts.position});return false;}
				
		if($pwdVal != $value){
			layer.tips(opts.errtip, $ele, {tips: [opts.position,'red']});
			$ele.select().val("");
			return false;
		}
	},
	
	udfsubmit:function($options,$parms){
		var defaultopts = {
			url:"index.php",
			type:"POST",
			dataType:"html",
			async:true,
			endEvent:function(){}					
		};
		
		var defaultParms = {
		
		};		
		var opts = $.extend({},defaultopts,$options);
		var parms = $.extend({},$defaultParms,$parms);
		
		$.ajax({
			async:opts.async,
			type:opts.type,
			url:opts.url,
			dataType:opts.dataType,
			data:{form:$parms},
			success: function(pass){
				
			}					
		});		
	}	
}
////////*******以下公共插件对象*****/////////////
jQuery.publicPlugin = {
	base64:{		
		 encode:function (input) { 
		 	  var _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";  
			  var output = "";  
			  var chr1, chr2, chr3, enc1, enc2, enc3, enc4;  
			  var i = 0; 
			  var  _utf8_encode = function(string) {  
				  string = string.replace(/\r\n/g,"\n");  
				  var utftext = "";  
				  for (var n = 0; n < string.length; n++){  
					  var c = string.charCodeAt(n);  
					  if (c < 128) {  
						  utftext += String.fromCharCode(c);  
					  } else if((c > 127) && (c < 2048)) {  
						  utftext += String.fromCharCode((c >> 6) | 192);  
						  utftext += String.fromCharCode((c & 63) | 128);  
					  } else {  
						  utftext += String.fromCharCode((c >> 12) | 224);  
						  utftext += String.fromCharCode(((c >> 6) & 63) | 128);  
						  utftext += String.fromCharCode((c & 63) | 128);  
					  }  
			 
				  }  
				  return utftext;  
			  } 
			  input = _utf8_encode(input);  
			  while (i < input.length) {  
				  chr1 = input.charCodeAt(i++);  
				  chr2 = input.charCodeAt(i++);  
				  chr3 = input.charCodeAt(i++);  
				  enc1 = chr1 >> 2;  
				  enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);  
				  enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);  
				  enc4 = chr3 & 63;  
				  if (isNaN(chr2)) {  
					  enc3 = enc4 = 64;  
				  } else if (isNaN(chr3)) {  
					  enc4 = 64;  
				  }  
				  output = output +  
				  _keyStr.charAt(enc1) + _keyStr.charAt(enc2) +  
				  _keyStr.charAt(enc3) + _keyStr.charAt(enc4);  
			  }  
			  return output;  
		  }, 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 		 
		decode:function(input) {
			  var _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";   
			  var output = "";  
			  var chr1, chr2, chr3;  
			  var enc1, enc2, enc3, enc4;  
			  var i = 0;  
			  var _utf8_decode = function (utftext) {  
				  var string = "";  
				  var i = 0;  
				  var c = c1 = c2 = 0;  
				  while ( i < utftext.length ) {  
					  c = utftext.charCodeAt(i);  
					  if (c < 128) {  
						  string += String.fromCharCode(c);  
						  i++;  
					  } else if((c > 191) && (c < 224)) {  
						  c2 = utftext.charCodeAt(i+1);  
						  string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));  
						  i += 2;  
					  } else {  
						  c2 = utftext.charCodeAt(i+1);  
						  c3 = utftext.charCodeAt(i+2);  
						  string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));  
						  i += 3;  
					  }  
				  }  
				  return string;  
			  }
			  input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");  
			  while (i < input.length) {  
				  enc1 = _keyStr.indexOf(input.charAt(i++));  
				  enc2 = _keyStr.indexOf(input.charAt(i++));  
				  enc3 = _keyStr.indexOf(input.charAt(i++));  
				  enc4 = _keyStr.indexOf(input.charAt(i++));  
				  chr1 = (enc1 << 2) | (enc2 >> 4);  
				  chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);  
				  chr3 = ((enc3 & 3) << 6) | enc4;  
				  output = output + String.fromCharCode(chr1);  
				  if (enc3 != 64) {  
					  output = output + String.fromCharCode(chr2);  
				  }  
				  if (enc4 != 64) {  
					  output = output + String.fromCharCode(chr3);  
				  }  
			  }  
			  output = _utf8_decode(output);  
			  return output;  
		  } 	 
	}
}


