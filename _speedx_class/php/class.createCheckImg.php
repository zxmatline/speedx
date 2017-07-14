<?php
////////////////////////******验证码类*******//////////////////////////

/********************************************************************/

class createImageCode{
	private $fontPath = "./path"; //字体路径
	private $charset = "abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789";//去除了0o1liqQ一些容易混淆的字符
	private $codeNum = 4; //验证码字条个数
	private $codeWidth = 100; //验证码图片宽度
	private $codeHight = 25; //验证码图片高度
	private $codeImage; //内部使用，验证码图片句柄
	private $codeFont = array("BuxtonSketch.ttf","bgothm.ttf","LHANDW.TTF","quillscn.ttf","SourceSansPro-Italic.ttf");
	//private $codeFont = "BuxtonSketch.ttf"; //字体文件名
	private $fontSize = 20; //验证码字符大小
	private $outCodeSet = ""; //生成的完整验证码字符串
	
	function __construct($fontPath = "./font"){
		$this->fontPath = $fontPath;
	}	
	
/**************************生成验证码字符******************************/	
	private function createCode(){ 
		$this->outCodeSet = "";
		$charLen = strlen($this->charset) - 1;
		for($var = 0; $var < $this->codeNum; $var++){
			$this->outCodeSet .= $this->charset[mt_rand(0,$charLen)];
		}	
	}

/**************************生成验证码背景******************************/	
	private function codeBackground(){
		//ob_clean();
		$this->codeImage = imagecreatetruecolor($this->codeWidth,$this->codeHight);
		$backBrush = imagecolorallocate($this->codeImage,mt_rand(180,230),mt_rand(180,230),mt_rand(180,230));
		imagefill($this->codeImage,0,0,$backBrush);		
	}

/**************************生成验证码干扰******************************/
	private function imageRandom(){		
		for($tep = 1; $tep < 5; $tep++){ //生成干扰线
			$randomColor = imagecolorallocate($this->codeImage,mt_rand(100,200),mt_rand(100,200),mt_rand(100,200));
			imageline($this->codeImage,mt_rand(0,$this->codeWidth),mt_rand(0,$this->codeHight),mt_rand(0,$this->codeWidth),mt_rand(0,$this->codeHight),$randomColor);
		}
		
		for($tep = 1; $tep < 20; $tep++){//生成干扰字符
			$randomColor = imagecolorallocate($this->codeImage,mt_rand(0,150),mt_rand(0,150),mt_rand(0,150));
			imagestring($this->codeImage,mt_rand(1,6),mt_rand(0,$this->codeWidth),mt_rand(0,$this->codeHight),'*',$randomColor);		
		}			
	}
/**************************生成验证码文字图片******************************/
	private function imageCodeText(){
		$singleWidth = $this->codeWidth / $this->codeNum;
		for($var = 0; $var < $this->codeNum; $var++){
			$textColor = imagecolorallocate($this->codeImage,mt_rand(0,50),mt_rand(0,50),mt_rand(0,50));
			//echo $this->fontPath.$this->codeFont;
			imagettftext($this->codeImage,$this->fontSize,mt_rand(-30,30),$var*$singleWidth+mt_rand(1,5),$this->codeHight/1.4,$textColor,$this->fontPath."/".$this->codeFont[mt_rand(0,4)],$this->outCodeSet[$var]);
		}
	}
	
/**************************验证码图形输出******************************/
	public function outputImage(){
	ob_clean(); //如果无图片输出，请打开这句
	$this->createCode();
	$this->codeBackground();
	$this->imageCodeText();
	$this->imageRandom();
	header('Content-type:image/png');		
	imagepng($this->codeImage);
	imagedestroy($this->codeImage);	
}

/**************************获取验证码字符******************************/
	public function getCode(){
		//$this->createCode();
		$code = strtolower($this->outCodeSet);
		$this->outCodeSet = "";
		return $code;
	}
}

?>