<?php
class cropper{
	private $max_width = 800;
	private $max_height = 600;
	private $outDir = "/tempImages/";
	private $forwordText = "new";
	private $err = "success";
	
	function __construct($outDir = "./",$width = 800,$height = 600){
		if(!is_dir($outDir)){
			$this->outDir =  "./";
		}else{
			$outDir = rtrim($outDir,"/")."/";
			$this->outDir = $outDir;
		}
		$this->max_height = $height;
		$this->max_width = $width;
		$this->outDir = $outDir;
	}
	
	public function __set($varName,$varValue){$this->$varName = $varValue;}
	public function __get($varName){return $this->$varName;}
	
/******************检查文件类型是否是支持的文件类型：jpg,png,gif,bmp***********************/	
	private function checkImageType($src){
		if (!empty($src)) {
		  $type = exif_imagetype($src);	
  		}
		$src_img = false;		
		 switch ($type) {
			case IMAGETYPE_GIF:
			  $src_img = true;
			  break;	
			case IMAGETYPE_JPEG:
			  $src_img = true;
			  break;
	
			case IMAGETYPE_PNG:
			  $src_img = true;
			  break;
			case IMAGETYPE_WBMP:
			  $src_img = true;
			  break;
		 default:
		 	$this->err = "不支持的图片类型，只能是：jpg,gif,png,bmp类型,或文件不存在!";
		 }
		return $src_img;
	}
/******************创建基本的图片句柄，可以是透明背景，指定大小**********************/	
	private function createBasicImgHandle($width = 0,$height = 0,$transparent = false,$red =255,$green = 255,$blue = 255){
		$width = $width == 0 ? $this->max_width : $width;
		$height = $height == 0 ? $this->max_height : $height;
				
		$disImgHandle= imagecreatetruecolor($width,$height);
		if($transparent){
			imagealphablending($disImgHandle,true);		
			$black = imagecolorallocatealpha($disImgHandle,254,254,254,127);
			imagefill($disImgHandle,0,0,$black);
			//imagecolortransparent($disImgHandle,$black);
		}else{
			$black = imagecolorallocate($disImgHandle,$red,$green,$blue);
			imagefill($disImgHandle,0,0,$black); 
		}
		return $disImgHandle;
	}
/******************创建指定图像句柄**********************/	
	private function createImgHandleFromImage($src){	
		 if(!$this->checkImageType($src)){return false;}
		 $type = exif_imagetype($src);	
		 $src_img = false;		
		 switch ($type) {
			case IMAGETYPE_GIF:
			  $src_img = imagecreatefromgif($src);
			  break;	
			case IMAGETYPE_JPEG:
			  $src_img = imagecreatefromjpeg($src);
			  break;	
			case IMAGETYPE_PNG:
			  $src_img = imagecreatefrompng($src);
			  break;
			case IMAGETYPE_WBMP:
			  $src_img = imagecreatefromwbmp($src);
			  break;
			default:
			$this->err = "不支持的图片类型，只能是：jpg,gif,png,bmp类型,或文件不存在!";
		  }
		return $src_img;
    }
/******************创建黑白棋方格背景句柄***********************/	
	private function createChessHandle($width=0,$height=0,$chessSize=10,$gray_r=204,$gray_g=204,$gray_b=204,$white_r=255,$white_g=255,$white_b=255){
		$width = $width <= 0 ? $this->max_width : $width;
		$height = $height <= 0 ? $this->max_height : $height;
		
		$imgHandle = $this->createBasicImgHandle($width,$height);
		$h_Count = intval($width / $chessSize);
		$v_Count = intval($height / $chessSize);
		
		$gray = imagecreatetruecolor($chessSize,$chessSize);
		$white = imagecreatetruecolor($chessSize,$chessSize);	
		
		$grayBrush = imagecolorallocate($gray,$gray_r,$gray_g,$gray_b);
		$whiteBrush = imagecolorallocate($white,$white_r,$white_g,$white_b);
		$brush = null;
		
		imagefill($gray,0,0,$grayBrush);
		imagefill($white,0,0,$whiteBrush);	
		
		$backWidth = $h_Count*$chessSize;
		$backHeight = $v_Count * $chessSize;
		
		$back = imagecreatetruecolor($backWidth,$backHeight);
		
		for($v = 1;$v <= $v_Count; $v++){
			if($v % 2 ==0){
				$gray_t = $gray;
				$white_t = $white;
			}else{
				$gray_t = $white;
				$white_t = $gray;
			}
			for($h = 1;$h <= $h_Count; $h++){		
				$brush = ($h % 2 == 0) ? $gray_t : $white_t;	
				imagecopymerge($back,$brush,($h-1)*$chessSize,($v-1)*$chessSize,0,0,$chessSize,$chessSize,100);
			}
		}		
		imagecopyresampled ($imgHandle,$back,0,0,0,0,$width,$height,$backWidth,$backHeight);	
		imagedestroy($gray);
		imagedestroy($white);
		imagedestroy($back);
		return $imgHandle;
	}
/**************************************************    创建颜色背景句柄   *****************************************************/	
	private function createColorBackgroundHandle($width,$height,$red =255,$green = 255,$blue = 255){
		$width = $width <= 0 ? $this->max_width : $width;
		$height = $height <= 0 ? $this->max_height : $height;
		$imgHandle = $this->createBasicImgHandle($width,$height);
		$brush = imagecolorallocate($imgHandle,$red,$green,$blue);
		imagefilledrectangle ($imgHandle,0,0,imagesx($imgHandle),imagesy($imgHandle),$brush);
		return $imgHandle;	
	}
/******************创建指定大小的图片句柄，不足大小的，用指定方式填充***********************/	
	private function createFilledImageHandleToContainer($width = 0,$height = 0,$src = "",$backStyle = 2,$filledType = 1,$R = 255,$G = 255 ,$B = 255){
			/*
			*"backStyle: 1：颜色填充，2:黑白方格, 3:透明,4:正比缩放至指定大小 
			*FilledType"1：最小化正比缩放，2：最大化正比缩放居中，3：拉伸 
			*参数说明：当backStyle = 3时，$src不能为空
			*/
			
			$width = $width <= 0 ? $this->max_width : $width;
			$height= $height <= 0 ? $this->max_height : $height;
			
			switch($backStyle){
			case 1:
				$backgroundHandle = $this->createColorBackgroundHandle($width,$height,$R,$G,$B);
				break;
			case 2:
				$backgroundHandle = $this->createChessHandle($width,$height);					
				break;
			case 3:
				$backgroundHandle = $this->createBasicImgHandle($width,$height,true);
				break;
			default :
				$backgroundHandle = NULL;
			}
			
			if(!empty($src)){
				if(is_resource($src)){
					$foreImgHandle = $src;
				}else{
					$foreImgHandle = $this->createImgHandleFromImage($src);
				}
				if(!$foreImgHandle){return false;}
				
				//imagealphablending($backgroundHandle,false);
				//imagesavealpha($foreImgHandle,true);
				
				$dis_w = $width;
				$dis_h = $height;
				$dis_rat = $dis_w / $dis_h;			
				$src_w= imagesx($foreImgHandle);
				$src_h = imagesy($foreImgHandle);
				$src_rat = $src_w / $src_h;
							
				switch($filledType){
				case 1:
					$new_w = $dis_w;
					$new_h = $dis_h;
					
					$new_x = 0;
					$new_y = 0;
					
					if($src_rat > $dis_rat){
						$new_h = $dis_w / $src_rat;
						$new_y = ($dis_h - $new_h)/2;
					}
					if($src_rat  < $dis_rat){
						$new_w = $src_rat * $dis_h;
						$new_x = ($dis_w - $new_w) / 2;
					}
					if($backStyle > 3){
						$backgroundHandle = $this->createBasicImgHandle($new_w,$new_h);
						imagecopyresampled($backgroundHandle,$foreImgHandle,0,0,0,0,$new_w,$new_h,$src_w,$src_h);
					}else{
						imagecopyresampled($backgroundHandle,$foreImgHandle,$new_x, $new_y,0,0,$new_w,$new_h,$src_w,$src_h);
					}
					break;
				case 2:
					$new_w = $src_w;
					$new_h = $src_h;
					
					$new_x = 0;
					$new_y = 0;
					
					if($src_rat > $dis_rat){
						$new_w = $dis_rat * $src_h;
						$new_x = ($src_w- $new_w)/2;
					}
					if($src_rat  < $dis_rat){
						$new_h = $src_w / $dis_rat;
						$new_y = ($src_h - $new_h) / 2;
					}
					imagecopyresampled($backgroundHandle,$foreImgHandle,0,0,$new_x, $new_y,$dis_w,$dis_h,$new_w,$new_h);
					break;
				case 3:
					imagecopyresampled($backgroundHandle,$foreImgHandle,0,0,0,0,$width,$height,imagesx($foreImgHandle),imagesy($foreImgHandle));
					break;
				}	
				imagedestroy($foreImgHandle);
			}
		
		return $backgroundHandle;
	}
/******************翻转X***********************/
	private function flipXHandle($srcHandle){
		$width = imagesx($srcHandle); 
		$height = imagesy($srcHandle);
	
		$new = imagecreatetruecolor($width, $height);
		for($x=0;$x<$width;$x++){
			imagecopymerge($new,$srcHandle,$width-$x-1, 0, $x, 0, 1, $height,100);
		}	
		imagedestroy($srcHandle);
		return $new;
	}
/******************翻转Y***********************/
	private function flipYHandle($srcHandle){
	   $width = imagesx($srcHandle); 
	   $height = imagesy($srcHandle);

	   $new = imagecreatetruecolor($width, $height);
	   for($y=0;$y<$height;$y++){
		  imagecopymerge($new,$srcHandle,0,$height-$y-1,0,$y,$width,1,100);
	   }
	   imagedestroy($srcHandle);
	   return  $new;	
	}	
/******************************  翻转XY  ************************************/
	private function flipXYHandle($srcHandle,$x = 1,$y = 1){
	   if($x == -1){$srcHandle = $this->flipXHandle($srcHandle);}
	   if($y == -1){$srcHandle = $this->flipYHandle($srcHandle);}
	   return $srcHandle;
	}
/****************************** 将图像句柄转成图像文件 ************************************/
	private function createNewImage($imageHandle,$forwordText = "new"){
		$newfileName = $this->createNewFileName($forwordText);
		imagepng($imageHandle,$newfileName);
		imagedestroy($imageHandle);
		return $newfileName;		
	}
/****************************** 滤镜句柄 ************************************/
	private function filterHandle($imageHandle,$filtertype ,$arg1 = 0,$arg2 = 0,$arg3 = 0){
		/*
		**1： IMG_FILTER_NEGATE：将图像中所有颜色反转。
		**2： IMG_FILTER_GRAYSCALE：将图像转换为灰度的。  
		**3： IMG_FILTER_BRIGHTNESS：改变图像的亮度。用 arg1 设定亮度级别。 0-100 
		**4： IMG_FILTER_CONTRAST：改变图像的对比度。用 arg1 设定对比度级别。  0-100
		**5： IMG_FILTER_COLORIZE：与 IMG_FILTER_GRAYSCALE 类似，不过可以指定颜色。用 arg1，arg2 和 arg3 分别指定 red，blue 
				和 green。每种颜色范围是 0 到 255。  
		**6： IMG_FILTER_EDGEDETECT：用边缘检测来突出图像的边缘。  
		**7： IMG_FILTER_EMBOSS：使图像浮雕化。  
		**8： IMG_FILTER_GAUSSIAN_BLUR：用高斯算法模糊图像。  
		**9： IMG_FILTER_SELECTIVE_BLUR：模糊图像。  
		**10：IMG_FILTER_MEAN_REMOVAL：用平均移除法来达到轮廓效果。  
		**11：IMG_FILTER_SMOOTH：使图像更柔滑。用 arg1 设定柔滑级别。 
	    */
    	//imagealphablending($imageHandle, true); 
		//imagesavealpha($imageHandle, true); 
		
		switch($filtertype){
		case 1: 
			imagefilter($imageHandle,IMG_FILTER_NEGATE);
			break;
		case 2:
			imagefilter($imageHandle,IMG_FILTER_GRAYSCALE);
			break;
		case 3:
			if($arg1 == 0){$arg1 = 60;}
			imagefilter($imageHandle,IMG_FILTER_BRIGHTNESS,$arg1 );
			break;
		case 4:
			if($arg1 == 0){$arg1 = 50;}
			imagefilter($imageHandle,IMG_FILTER_CONTRAST,$arg1);
			break;
		case 5:
			if($arg1 == 0){$arg2 = 255;}
			imagefilter($imageHandle,IMG_FILTER_COLORIZE,$arg1,$arg2,$arg3,50);
			break;
		case 6:
			imagefilter($imageHandle,IMG_FILTER_EDGEDETECT);
			break;
		case 7:
			imagefilter($imageHandle,IMG_FILTER_EMBOSS);
			break;
		case 8:
			imagefilter($imageHandle,IMG_FILTER_GAUSSIAN_BLUR);
			break;
		case 9:			
			imagefilter($imageHandle,IMG_FILTER_SELECTIVE_BLUR);
			break;
		case 10:
			imagefilter($imageHandle,IMG_FILTER_MEAN_REMOVAL);
			break;
		case 11:
			if($arg1 == 0){$arg1 = 100;}
			imagefilter($imageHandle,IMG_FILTER_SMOOTH,$arg1);
			break;
		case 12:
			imagefilter($imageHandle,IMG_FILTER_PIXELATE,5,false);
			break;
		default:
			$imageHandle = $imageHandle;
		}
		return $imageHandle;
	}
/******************************产生新文件名***********************************************/	
	private function createNewFileName($forwordText = "new",$extention = ".png"){
		$tm = date("YmdHis",time()).mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
		return $this->outDir.$forwordText."_".$tm.$extention;		
	}
/*************************************  旋转句柄 **************************************/
	private function rotateHandle($imageHandle,$rotang = 0){	//$imageHandle必须是png图片	
		imagealphablending($imageHandle, false); 
		imagesavealpha($imageHandle, true); 
	
		$imageHandle = imagerotate($imageHandle, $rotang, imageColorAllocateAlpha($imageHandle, 0, 0, 0, 127));
		return $imageHandle;
	}
/*************************************  旋转输出 **************************************/
	public function rotate($image,$rotang = 0){	//$imageHandle必须是png图片	
		$imageHandle = $this->createImgHandleFromImage($image);
		if(!$imageHandle){return false;}
		$imageHandle = $this->rotateHandle($imageHandle,$rotang);
		return $this->createNewImage($imageHandle);
	}
/******************************  翻转输出  ************************************/
	public function flip($src,$x = 1,$y = 1){
	   if(!$this->checkImageType($src)){return false;}	
	   $srcHandle = $this->createImgHandleFromImage($src);
	   return $this->createNewImage($this->flipXY($srcHandle,$x,$y));
	}
/*************************************  缩略图  **************************************/
	public function thumb($image,$width = 100,$height = 100,$eraseSource = false){
		if(!$this->checkImageType($image)){return false;}
		$newImgHandle = $this->createFilledImageHandleToContainer($width,$height,$image, 3,	1);
		
		$fileinfo = pathinfo($image);
		$newFileName = $this->createNewFileName("thumbnail_");
		
		if(is_file($newFileName)){unlink($newFileName );}		
		
		imagepng($newImgHandle,$newFileName);
		imagedestroy($newImgHandle);
		if($eraseSource){unlink($image);}
		return $newFileName;		
	}
/************************************* 	改变到指定大小 	*************************************/
	public function resizeImgToContainer($image,$width = 480,$height = 480,$eraseSource = false){
		if(!$this->checkImageType($image)){return false;}
		$imageHandle = $this->createFilledImageHandleToContainer($width,$height,$image,4,1);
    	imagealphablending($imageHandle, false); 
		imagesavealpha($imageHandle, true); 
		$fileinfo = pathinfo($image);
		$ext = ".".$fileinfo ['extension'];
		$newFileName = $this->createNewFileName("new_", $ext);
		
		if(is_file($newFileName)){unlink($newFileName );}
				
		 $type = exif_imagetype($image);	  	
		 switch ($type) {
		 case IMAGETYPE_GIF:
			imagegif($imageHandle,$newFileName);
			break;	
		 case IMAGETYPE_JPEG:
			imagejpeg($imageHandle,$newFileName,80);
			break;	
		 case IMAGETYPE_PNG:
			imagepng($imageHandle,$newFileName);
			break;	
		 case IMAGETYPE_WBMP:
			imagewbmp($imageHandle,$newFileName);
			break;	
		 }		
		imagedestroy($imageHandle);
		if($eraseSource){unlink($image);}
		return $newFileName;		
	}
/****************************** 调整大小，不足的透明 ************************************/
	public function resizeImgToPng($src,$width = 0,$height = 0){
		$width = $width <= 0 ? $this->max_width : $width;
		$height= $height <= 0 ? $this->max_height : $height;
		$imageHandle = $this->checkImageType($src);
		if(!$imageHandle){return false;}
		$imageHandle = $this->createFilledImageHandleToContainer($width,$height,$src,3,1);
		return $this->createNewImage($imageHandle);			
	}
/****************************** 检查图片大小，并调整大小，不足的透明化 ************************************/
	public function checkImgSizeToPng($src,$width = 0,$height = 0){
		$width = $width <= 0 ? $this->max_width : $width;
		$height= $height <= 0 ? $this->max_height : $height;
		$imageHandle = $this->createImgHandleFromImage($src);
		if(!$imageHandle){return false;}
		$natureWidth = imagesx($imageHandle);
		$natureHeight = imagesy($imageHandle);
		imagedestroy($imageHandle);
		
		if($natureWidth > $width || $natureHeight > $height){
			$imageHandle = $this->createFilledImageHandleToContainer($width,$height,$src,4,1);
			$newFile = $this->createNewImage($imageHandle);
			if(!is_file($src)){@unlink($src);}
			return $newFile;
		}else{
			return $src;
		}
	}
/****************************** 加水印 ************************************/
	public function addFlag($src = "",$flagImg = "",$position = "BR",$text = "",$testPosition = "BR",$fontFamily = "default.ttf",$fontsize = 18,$opacity = 80,$textR = 255,$textG = 0,$textB = 0){
		if(!$this->checkImageType($src)){return false;}		
		if(empty($flagImg) && empty($text)){$this->err = "没有设置水印";return false;}
		$srcHandle = $this->createImgHandleFromImage($src);
		
		if(empty($fontFamily)){$fontFamily = "default.ttf";}
		if($fontsize == 0){$fontsize = 18;}
		
		$src_w = imagesx($srcHandle);
		$src_h = imagesy($srcHandle);		
		$padding = 5;
		
		if(!empty($flagImg)){
			if(!$this->checkImageType($flagImg)){return false;}
			$flagHandle = $this->createImgHandleFromImage($flagImg);
			$flag_w = imagesx($flagHandle);
			$flag_h = imagesy($flagHandle);
			imagealphablending($flagHandle,false);
			imagesavealpha($flagHandle,true);
			
			if($flag_h > $src_h  || $flag_w > $src_w){
				$this->err = "水印不能比原图像大";
				imagedestroy($flagHandle);
				imagedestroy($srcHandle);
				return false;
			}
			
			$XL= 0 +  $padding;
			$XM = ($src_w - $flag_w) / 2;
			$XR = ($src_w -  $flag_w) - $padding;
			
			$YT = 0 + $padding;
			$YM = ($src_h - $flag_h) / 2;
			$YB = ($src_h - $flag_h) - $padding; 	
			
			switch($position){
			case "TL":
				imagecopyresampled($srcHandle,$flagHandle,$XL,$YT,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			case "TC":
				imagecopyresampled($srcHandle,$flagHandle,$XM,$YT,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			case "TR":
				imagecopyresampled($srcHandle,$flagHandle,$XR,$YT,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			case "ML":
				imagecopyresampled($srcHandle,$flagHandle,$XL,$YM,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			case "MC":
				imagecopyresampled($srcHandle,$flagHandle,$XM,$YM,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			case "MR":
				imagecopyresampled($srcHandle,$flagHandle,$XR,$YM,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			case "BL":
				imagecopyresampled($srcHandle,$flagHandle,$XL,$YB,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			case "BC":
				imagecopyresampled($srcHandle,$flagHandle,$XM,$YB,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			case "BR":
				imagecopyresampled($srcHandle,$flagHandle,$XR,$YB,0,0,$flag_w,$flag_h,$flag_w,$flag_h);
				break;
			}
			imagedestroy($flagHandle);
		}
		
		if(!empty($text)){
			$array = imageftbbox($fontsize,0,"default.ttf",$text );
			$w = abs($array[2] - $array[0]);
			$h = abs($array[5] - $array[0]);
			$imgcolor = imagecolorallocate($srcHandle,$textR,$textG,$textB);
			
			$XL= 0 +  $padding;
			$XM = ($src_w - $w) / 2;
			$XR = ($src_w -  $w) - $padding;
			
			$YT = 0 + $padding + $h;
			$YM = $src_h / 2 ;
			$YB = $src_h - $padding ; 	
			
			switch($testPosition){
			case "TL":
				imagefttext($srcHandle,$fontsize,0,$XL,$YT,$imgcolor,$fontFamily,$text);
				break;
			case "TC":
				imagefttext($srcHandle,$fontsize,0,$XM,$YT,$imgcolor,$fontFamily,$text);
				break;
			case "TR":
				imagefttext($srcHandle,$fontsize,0,$XR,$YT,$imgcolor,$fontFamily,$text);
				break;
			case "ML":
				imagefttext($srcHandle,$fontsize,0,$XL,$YM,$imgcolor,$fontFamily,$text);
				break;
			case "MC":
				imagefttext($srcHandle,$fontsize,0,$XM,$YM,$imgcolor,$fontFamily,$text);
				break;
			case "MR":
				imagefttext($srcHandle,$fontsize,0,$XR,$YM,$imgcolor,$fontFamily,$text);
				break;
			case "BL":
				imagefttext($srcHandle,$fontsize,0,$XL,$YB,$imgcolor,$fontFamily,$text);
				break;
			case "BC":
				imagefttext($srcHandle,$fontsize,0,$XM,$YB,$imgcolor,$fontFamily,$text);
				break;
			case "BR":
				imagefttext($srcHandle,$fontsize,0,$XR,$YB,$imgcolor,$fontFamily,$text);
				break;
			}
		}
		return $this->createNewImage($srcHandle);	
	}
/************************************  滤镜输出  ******************************************/
	public function filter($src,$filtertype = 0 ,$arg1 = 0,$arg2 = 0,$arg3 = 0){
		$imageHandle = $this->createImgHandleFromImage($src);
		if(!$imageHandle){return false;}
		$filterFile = $this->filterHandle($imageHandle,$filtertype,$arg1,$arg2,$arg3);
		//imagedestroy($imageHandle);
		return $this->createNewImage($filterFile,"filterNew");
	}		

/************************************  裁剪  ******************************************/
	public function cropper($options = array()){
		$default = array(
				"canvasImgSrc" => "",
				"curImageSrc:" => "",
				"naturalWidth" => 0,
				"naturalHeight" => 0,
				"containerWidth" => 0,
				"containerWidth" => 0,				
				"canvasImgCurWidth" => 0,
				"canvasImgCurHeight" => 0,
				"canvasImgTop" => 0,
				"canvasImgLeft" => 0,
				"cropBoxWidth" => 0,
				"cropBoxHeight" => 0,
				"cropBoxTop" => 0,
				"cropBoxLeft" => 0,
				"flipX" => 1,
				"flipY" => 1,
				"rotate" => 0,
				"filterType" => 0,
				"tplName" => "tpl_upload",
		);
				
		$opt = array_merge($default,$options);
	    if(!$this->checkImageType($opt["curImageSrc"])){return false;}
		
	   	$srcHandle = $this->createImgHandleFromImage($opt["curImageSrc"]);
		$srcHandle = $this->flipXYHandle($srcHandle,$opt["flipX"],$opt["flipY"]);
		$srcHandle = $this->rotateHandle($srcHandle,-$opt["rotate"]);
		
		$srcHandle = $this->createFilledImageHandleToContainer($opt["canvasImgCurWidth"],$opt["canvasImgCurHeight"],$srcHandle,3,1);
		//return $this->createNewImage($srcHandle);
		if($opt["containerWidth"]==0){$opt["containerWidth"] = $this->max_width;}
		if($opt["containerHeight"]==0){$opt["containerHeight"] = $this->max_height;}
		
		
		$backImgHandle = $this->createFilledImageHandleToContainer($opt["containerWidth"],$opt["containerHeight"],"",2,1);
		imagealphablending($backImgHandle,true);
		
		$canvasImgNatureWidth = imagesx($srcHandle);
		$canvasImgNatureHeight = imagesy($srcHandle);
		
		$containerWidth = $opt["containerWidth"];
		$containerHeight = $opt["containerHeight"];
		
		$canvasLeft = $opt["canvasImgLeft"];
		$canvasTop = $opt["canvasImgTop"];
		$canvasWidth = $opt["canvasImgCurWidth"];
		$canvasHeight = $opt["canvasImgCurHeight"];	
		
		$selectBoxLeft = $opt["cropBoxLeft"];
		$selectBoxTop = $opt["cropBoxTop"];
		$selectBoxWidth = $opt["cropBoxWidth"];
		$selectBoxHeight = $opt["cropBoxHeight"];
				
		imagecopyresampled($backImgHandle,$srcHandle,$canvasLeft,$canvasTop,0,0,$canvasWidth,$canvasHeight,$canvasImgNatureWidth,$canvasImgNatureHeight);
		
		imagedestroy($srcHandle);
		
		$newImg = $this->createBasicImgHandle($selectBoxWidth,$selectBoxHeight);
		imagecopyresampled($newImg,$backImgHandle,0,0,$selectBoxLeft,$selectBoxTop,$selectBoxWidth,$selectBoxHeight,$selectBoxWidth,$selectBoxHeight);
		imagedestroy($backImgHandle);
		//imagesavealpha($newImg,true);	
		return $this->createNewImage($newImg);
	}
}
?>