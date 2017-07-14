<?php
//树叶
class speedx_element_leaf{
	private $type = "";
	private $attr = array();
	private $content = "";
}
//树干
class speedx_element_trunk{
	
}
//树枝
class speedx_element_branch{
	
}

class speedx_element_tree{
	
}


/***
*******************************************************************
***********   闭合标签类   ***********
*******************************************************************
*/

class speedx_html_tag_close{
	private $tagName = "";
	private $tag_attr = array();
	private $content_index = array();
	private $content_var = array();
	
	
	public function __constructure($tag = ""){
		$this->tag_start = "<" . $tag .">";
		$this->tag_end = "</" .$tag .">";
	}
	
	public function attr_set($attrName,$attrValue){
		if(!empty($attrName)){
			if(!is_null($attrValue)){
				$this->tag_attr[$attrName] = $attrValue;
			}
		}
		
	}
	
	public function attr_get($attrName){
		if(!empty($attrName)){
			if(array_key_exists($attrName)){
				return $this->tag_attr[$attrName];
			}else{
				return NULL;
			}
		}
	}
	
	public function attr_del($attrName){
		if(array_key_exists($this->tag_attr[$attrName])){
			$this->tag_attr[$attrName] = NULL;
		}
	}
	
	public function content_append($con_key,$con_var){
		$this->content_index[] = $con_key;
		$this->content[$con_key] = $con_var;
	}
	
	public function content_befor($rel_key,$con_Key,$con_var){
		if(in_array($rel_key,$this->content_index)){
			if(in_array($con_Key,$this->content_index)){
				$this->err_no = 1;
				$this->err_msg = "要插入的标签内容：[" . $con_key ."]已经存在";
				return false;				
			}else{
				$this->content_var[$con_key] = $con_var;
				$index = array_search($rel_key,$this->content_index);
				array_splice($this->content_index,$index,0,$con_Key);
				
				$this->err_no = 0;
				$this->err_msg = "";
				return true;
			}
		}else{
			$this->content_var[$con_key] = $con_var;			
			array_push($this->content_index,$con_Key);
			$this->err_no = 0;
			$this->err_msg = "";
			return true;
		}		
	}
	
	public function content_after($name,$cont){}	
	
	public function tag_dump(){
		
	}
	
}

/**
**开放标签类
*/

class speedx_html_tag_open{
	private $endFlag = "";
	public function __constructure($endTagWithFlag = "/"){
		$this->endFlag = "/";
	}
}

/**
**变量标签
*/

class speedx_var_tag{
	
}
$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$html = htmlentities($html);
echo $html;

?>