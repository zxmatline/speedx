<?php
/**
 * Created by SPEEDX
 * User: 御风而行
 * Date: 2016/4/10
 * Time: 21:05
 */


class speedx {
	protected static $publicVar = array();
	protected static $privateVar = array();
	protected static $groupVar = array();
	protected static $layoutVar = array();
    protected static $vars= array();//模块变量列表	
	
    /*
    *****变量内容赋值
    */
    public static function assign($key, $value){
        self::$vars[$key]=$value;
    }


    public static function replace_with_assign($text){
        $keys = array_keys(self::$vars);
        $vals = array_values(self::$vars);

        foreach($keys as $key=>$value){
            $keys[$key] = '/\<\{\\$\s*'.$value.'\s*\}\>';
        }
		self::$vars = array();

        return preg_replace($keys,$vals,$text);
    }
	
	public static function find_groups($html){
		$groupVarPreg = '/\<\{\\$group\s*=\s*([\s\S]+?)\}\>/';
		$matchs = array();
		preg_match_all($groupVarPreg,$html,$matchs);
		if(isset($matchs[1])){
			$matchs = array_unique($matchs[1]);
			return $matchs[1];
		}
		return $matchs;		
	}
	
	
	public static function find_default_vars($html){
		$varPreg = '/\<\{\\$\s*([\s\S]+?)\s*\}\>/';
		$matchs = array();
		preg_match_all($varPreg,$html,$matchs);
		if(isset($matchs[1])){return $matchs[1];}
		return $matchs;		
	}
	
	public static function find_public_vars($html){
		$publicVarPreg = '/\<\{\\$public\s*=\s*([\s\S]+?)\}\>/';
		$matchs = array();
		preg_match_all($publicVarPreg,$html,$matchs);
		if(isset($matchs[1])){return $matchs[1];}
		return $matchs;		
	}

    
    public function selectWithAssign($tplfile,$sectionName="", $type = "section"){       
		/**
			关于匹配$的问题：想要匹配字符$，正则就得是\$，然后再定义正则字符串，\$中的\也需要转义，
			所以\就变成了\\，最后就变成了\\$。 
			(另外，如果定义正则字符串使用的是双引号字符串，那么结果就得写三个反斜线了，\\\$)
		*/
		
		$privateVarPreg = '/\<\{\\$private\s*=\s*([\s\S]+?)\}\>/';
		$groupVarPreg = '/\<\{\\$group\s*=\s*([\s\S]+?)\}\>/';
		$layoutVarPreg = '/\<\{\\$layout\s*=\s*([\s\S]+?)\}\>/';
		$varPreg = '/\<\{\\$var\s*=\s*([\s\S]+?)\}\>/';       
    }


    /*
    ****替换模块文件内指定区域的模块变量,返回替换后的内容
    */
   public function remove_notes($content){
	   $content = preg_replace("/\/\*[\s\S]+?\*\//", "", $content, true);
	   $content = preg_replace("/\<!--[\s\S]+?--\>/", "", $content, true);
   }
}
?>