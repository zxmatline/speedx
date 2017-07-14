<?php
	date_default_timezone_get('Asia/Shanghai');
	
	if(!defined('_SPEEDX_DOCUMENT_ROOT_')){
		define('_SPEEDX_DOCUMENT_ROOT_',rtrim($_SERVER["DOCUMENT_ROOT"],"/")."/");	
	}
	
	$_speedx_project_root = NULL;
	
	if(!is_null($_speedx_project_root)){
		define("_SPEEDX_PROJECT_ROOT_",_SPEEDX_DOCUMENT_ROOT_);	
	}else{
		define("_SPEEDX_PROJECT_ROOT_",_SPEEDX_DOCUMENT_ROOT_ . $_speedx_project_root ."/");	
	}
	
	if(!defined('_SPEEDX_CLASS_'))define('_SPEEDX_CLASS_',_SPEEDX_PROJECT_ROOT_ . "_speedx_class/");
	if(!defined('_SPEEDX_CLASS_PHP_'))define('_SPEEDX_CLASS_PHP_',_SPEEDX_PROJECT_ROOT_ . "_speedx_class/php/");
	if(!defined('_SPEEDX_CLASS_JS_'))define('_SPEEDX_CLASS_JS_',_SPEEDX_PROJECT_ROOT_ . "_speedx_class/js/");
	if(!defined('_SPEEDX_CLASS_CSS_'))define('_SPEEDX_CLASS_CSS_',_SPEEDX_PROJECT_ROOT_ . "_speedx_class/css/");
	
	if(!defined('_SPEEDX_ARCHITECTURE_'))define('_SPEEDX_ARCHITECTURE_',_SPEEDX_PROJECT_ROOT_ . "_speedx_architecture/");
	
	if(!defined('_SPEEDX_CONFIGURE_'))define('_SPEEDX_CONFIGURE_',_SPEEDX_PROJECT_ROOT_ . "_speedx_configure/");
	if(!defined('_SPEEDX_PLUGINS_'))define('_SPEEDX_PLUGINS',_SPEEDX_PROJECT_ROOT_ . "_speedx_plugins/");
	if(!defined('_SPEEDX_LOG_'))define('_SPEEDX_LOG_',_SPEEDX_PROJECT_ROOT_ . "_speedx_log/");
	if(!defined('_SPEEDX_RSA_'))define('_SPEEDX_RSA_',_SPEEDX_PROJECT_ROOT_ . "_speedx_rsa/");
	if(!defined('_SPEEDX_CACHE_'))define('_SPEEDX_CACHE_',_SPEEDX_PROJECT_ROOT_ . "_speedx_chache/");
	if(!defined('_SPEEDX_ACTIONS_'))define('_SPEEDX_ACTIONS_',_SPEEDX_PROJECT_ROOT_ . "_speedx_actions/");
	if(!defined('_SPEEDX_CMS_'))define('_SPEEDX_CMS_',_SPEEDX_PROJECT_ROOT_ . "_speedx_cms/");
	
	if(!defined('_SPEEDX_PLATFORM_'))define('_SPEEDX_PLATFORM_',_SPEEDX_PROJECT_ROOT_ . "_speedx_platform/");
	if(!defined('_SPEEDX_PLATFORM_WIN_'))define('_SPEEDX_PLATFORM_WIN_',_SPEEDX_PROJECT_ROOT_ . _SPEEDX_PLATFORM_ . "win/");
	if(!defined('_SPEEDX_PLATFORM_WX_'))define('_SPEEDX_PLATFORM_WX_',_SPEEDX_PROJECT_ROOT_ . _SPEEDX_PLATFORM_ . "wx/");
	if(!defined('_SPEEDX_PLATFORM_ZFB_'))define('_SPEEDX_PLATFORM_ZFB_',_SPEEDX_PROJECT_ROOT_ . _SPEEDX_PLATFORM_ . "zfb/");
	if(!defined('_SPEEDX_PLATFORM_YD_'))define('_SPEEDX_PLATFORM_YD_',_SPEEDX_PROJECT_ROOT_ . _SPEEDX_PLATFORM_ . "yd/");
	
	if(!defined('_SPEEDX_PAGES_'))define('_SPEEDX_PAGES_',_SPEEDX_PROJECT_ROOT_ . "_speedx_pages/");
	if(!defined('_SPEEDX_CELL_'))define('_SPEEDX_CELL_',_SPEEDX_PROJECT_ROOT_ . "_speedx_cell/");
	if(!defined('_SPEEDX_LAYOUT_'))define('_SPEEDX_LAYOUT_',_SPEEDX_PROJECT_ROOT_ . "_speedx_layout/");
	if(!defined('_SPEEDX_TEMP_'))define('_SPEEDX_TEMP_',_SPEEDX_PROJECT_ROOT_ . "_speedx_temp/");
	if(!defined('_SPEEDX_TEST_'))define('_SPEEDX_TEST_',_SPEEDX_PROJECT_ROOT_ . "_speedx_test/");
	if(!defined('_SPEEDX_MATERIAL_'))define('_SPEEDX_MATERIAL_',_SPEEDX_PROJECT_ROOT_ . "_speedx_material/");
	if(!defined('_SPEEDX_FONTS_'))define('_SPEEDX_FONTS_',_SPEEDX_PROJECT_ROOT_ . "_speedx_fonts/");
	if(!defined('_SPEEDX_TEMPLATE_'))define('_SPEEDX_TEMPLATE_',_SPEEDX_PROJECT_ROOT_ . "_speedx_template/");	
	
	define("TAG_ALONE",0);
	define("TAG_OPEN",1);
	define("TAG_OPEN_WITH_SLASH",2);
	define("TAG_CLOSE",3);

?>