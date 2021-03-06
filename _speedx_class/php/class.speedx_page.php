<?php
/*
*	define("TAG_ALONE",0);
*	define("TAG_OPEN",1);
*	define("TAG_OPEN_WITH_SLASH",2);
*	define("TAG_CLOSE",3);
*/
	define("TAG_ALONE",0);
	define("TAG_OPEN",1);
	define("TAG_OPEN_WITH_SLASH",2);
	define("TAG_CLOSE",3);
	
/**
**创建标签类
*/	
	class speedx_tag{
		private $tagHead = "";
		private $aloneTag = false;
		private $openTag = false;
		private $openTagWithSlash = false;		
		private $closeTag = true;
		private $attr = array();
		private $tagContent = "";
		
		public function __constructure($tagHead = "",$content = ""){
			$this->tagHead = $tagTitle;
			$this->tagContent = $content;
		}
		public function set_tag_type($tag_type = TAG_alone){
			switch($tag_type) {
			case TAG_ALONE: 
				$this->aloneTag = true;
				$this->openTag = false;
				$this->openTagWithSlash = false;
				$this->doubleTag = false;
				break;
			case TAG_OPEN:
				$this->aloneTag = false;
				$this->openTag = true;
				$this->openTagWithSlash = false;
				$this->doubleTag = false;
				break;
			case TAG_OPEN_WITH_SLASH:	
				$this->aloneTag = false;
				$this->openTag = false;
				$this->openTagWithSlash = true;
				$this->doubleTag = false;
				break;			
			case TAG_CLOSE:	
				$this->aloneTag = false;
				$this->openTag = false;
				$this->openTagWithSlash = false;
				$this->doubleTag = true;
				break;			
			}		
		}
		
		public function set_tag_head($headTitle = ""){
			$this->tagHead = $headTitle;
		}
		
		public function set_tag_attr($attrName,$attrValue){
			$this->attr[$attrName] = $attrValue;
		}
		
		public function get_tag_attr($attrName){
			if(isset($this->attr[$attrName])){
				return $this->attr[$attrName];			
			}else{
				return false;			
			}
		}
		
		public function set_tag_content($content = ""){
			$this->tagContent = $content;
		}
		
		public function get_tag_content(){
			return $this->tagContent;		
		}
		
		public function get_tag_html(){
			$tagStart = "";
			$tagEnd = ""; 
			$attrString = "";
			$content = "";
			$attr = $this->attr;
			
			if($this->aloneTag){				
				if(!empty($attr)){
					foreach($attr as $value){
						$attrString = $attrString .  ' "' .$value . '"'; 
					}										
				}
				$tagHtml = '<' . $this->tagHead .  $attrString . '>';
				return $tagHtml;				
			}
			
			if($this->openTag){
				if(!empty($attr)){
					foreach($attr as $index => $value){
						$attrString = $attrString . " " . $index . '=' . '"' .$value . '"'; 
					}										
				}
				$tagHtml = '<' . $this->tagHead .  $attrString . '>';
				return $tagHtml;
			}
			
			if($this->openTagWithSlash){
				if(!empty($attr)){
					foreach($attr as $index => $value){
						$attrString = $attrString . " " . $index . '=' . '"' .$value . '"'; 
					}										
				}
				$tagHtml = '<' . $this->tagHead .  $attrString . ' />';
				return $tagHtml;
			}
			
			if($this->closeTag){
				if(!empty($attr)){
					foreach($attr as $index => $value){
						$attrString = $attrString . " " . $index . '=' . '"' .$value . '"'; 
					}										
				}
				
				$tagStart = '<' . $this->tagHead .  $attrString . '>';
				$tagEnd = '</' . $this->tagHead . '>';
				$tagHtml = $tagStart . $this->tagContent . $tagEnd;
				return $tagHtml;
			}
		}	
		
		public function __toString(){
			return $this->get_tag_html();
		}		
	}
	
	/*
	class speedx_structure{
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
		<head>
			<meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<{$envCSS}>
			<{$linkCSS}>
			<title><{$title}></title>
			<{$innerCSS}>
			<{$sessionStart}>
		</head>
		
		<body>
		<{$layout}>
		<{$envJS}>
		<{$linkJS}>
		<script type="text/javascript" language="javascript">
			<{$innerJS}>
			$(function(){
				<{$innerJQ}>
		 	});
		</script>	
		</body>
		</html>			
	}
	*/
	
	class speedx_structure{
		private $docType = null;
		private $meta = array();
		private $title = null;
		private $env_css_link = array();
		private $env_css_code = array();
		private $env_session = array();
		private $body = array();
		private $layout = null;
		private $env_js_link = array();
		private $env_jq_link = array();
		private $env_js_code = array();
		private $env_jq_code = array();
				
		public function __constructure($strucName){
			
		}

		public function   
	}
	
	
	class speedx_architecture{
		private $cssFile = array();
		private $cssCode = array();
		private $jsFile = array();
		private $jsCode = array();
		private $jqCode = array();
		
		public function __constructure(){}
		public function set_cssFile(){}	
	}
	
	class speedx_architecture{
		
	}
	
	class speedx_layout{
	
	}
	
	class speedx_cell{
	
	}

	class speedx_page{
		private $structure = null;
		private $architecture = null;
		private $layout = null;
		private $group_cell = null;
		private $title = "speedx测试平台";
		private $html = "";		
		
		
		public function __constructure($title = ""){
			$this->title = $title;		
		}
		
		public function set_title($title = ""){
			$this->title = $title;		
		}
		public function load_default_structure(){}
		public function load_udf_structure($strucName){}
		public function load_default_architecture(){}
		public function load_udf_architecture($archiName){}
		
		public function load_layout($layoutName){}
		//public function load_
		
	
	}
	
	//$page_index = new speedx_page("馨腾教育网络平台");
	//$page_index->load_default_structure();
	
/*
	$abc = new speedx_tag();
	$abc->set_tag_type(TAG_ALONE);
	$abc->set_tag_attr("type_one","-//W3C//DTD XHTML 1.0 Transitional//EN");
	$abc->set_tag_attr("type_two","http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd");
	$abc->set_tag_head('!DOCTYPE html PUBLIC');
	echo htmlentities($abc->get_tag_html());
	echo "<br/>";
	$tag_open = new speedx_tag();
	$tag_open->set_tag_type(TAG_OPEN);
	$tag_open->set_tag_head("meta");	
	$tag_open->set_tag_attr("name","viewport");
	$tag_open->set_tag_attr("content","width=device-width,initial-scale=1");
	echo htmlentities($tag_open->get_tag_html());
	echo "<br/>"; 
	
	$tag_img = new speedx_tag();
	$tag_img->set_tag_type(TAG_OPEN_WITH_SLASH);
	$tag_img->set_tag_head("img");
	$tag_img->set_tag_attr("src","/var/www/html/test.img");
	echo(htmlentities($tag_img->get_tag_html()));
	echo "<br/>"; 	
	
	$tag_close = new speedx_tag();
	$tag_close->set_tag_type(TAG_CLOSE);
	$tag_close->set_tag_head("html");
	$tag_close->set_tag_attr("xmlns","http://www.w3.org/1999/xhtml");
	$tag_close->set_tag_attr("lang","zh-CN");
	$tag_content = '<{$envCSS}>
			<{$linkCSS}>
			<title><{$title}></title>
			<{$innerCSS}>
			<{$sessionStart}>';
	$tag_close->set_tag_content($tag_content);
	echo(htmlentities($tag_close));
	echo "<br/>";
	
	echo $tag_close->get_tag_attr("lang");
*/

