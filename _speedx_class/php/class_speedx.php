<?php
class speedx_storage_define{
	private $type = "";
	private $name = "";
	private $value = "";
}

class speedx_storage_create{
	
}


/**
**基础页面结构体
*/
class speedx_default_structure{
	const DOCTYPE = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'; 
	
	public $doctype = "";
	public $html = "";
	
	public function __constructure(){}
	public function __toString(){}
}



/**
**页面架构
*/
class speedx_archi{
	public $structure = NULL;
	public $name = "";
	public $css = "";
	public $js = "";
	public $fonts = "";
	public function __constructure(){
		
	}
	
}
/**
**页面布局
*/
class speedx_layout{
	
}
/**
**页面组件
*/
class speedx_component{
	public $requireEvn = array();
	
}


/****
****页面组合类：speedx_html
*/

class speedx_html{
	public $title = "";
	public $name = "";
	public $output_dir = "";
	
	public $errNO = 0;
	public $errMessage = "";
	public $config = "";
	
	public $html = "";
	public $body = "";
	
	private $archi = array();
	private $layout = array();
	private $components = array();
	
	private $archi_js = array();
	private $archi_css = array();
	
	private $layout_js = array();
	private $layout_css = array();
	
	private $compo_js_link = array();
	private $compo_js_code = array();
	private $compo_css_link = array();
	private $compo_css_code = array();
	private $compo_html_code = array();	
	
	public function __constructure(){}
	
	private function analyze_component(){}	
	
	public function show_config(){}
	public function show_archi(){}
	public function show_compos(){}
	
	public function html_reset(){}
	public function load_default(){}
	public function __toString(){}
}

/****
****站点复合类:speedx
*/
class speedx{
	private $html = array();
	public function __constructor(){}
	public function insert_befor(){}
}

/**
********************************************
**代码测试
********************************************
*/


$page = new speedx_html();
$page->compnent_default = NULL;
$page->output_dir = "";
$page->name = "";
$page->compnents = array(
	"autoplay" => $public_component_autoplay,
	"mainMenu" => $public_comonent_mainMenu
);

$page->load_archi("jquery","bootstrap");
$page->load_layout("xinteng_first");
$page->title = "芜湖馨腾教育"; 
$page->load_component("footer",$public_compnent_footer);
$page->load_component_all();


$page->done(); 
$page->test();


?>