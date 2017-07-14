<?php
class saveRemoteImage{
	private $stateInfo = "";
	private $config = array("allowFiles"=>array(".png", ".jpg", ".jpeg", ".gif"));
    private $oriName; //原始文件名
    private $fullName; //完整文件名,即从当前配置目录开始的URL
    private $fileType; //文件类型
	public $outPath = "/temp/";
	
    public function saveRemote($url)
    {
        $imgUrl = htmlspecialchars($url);
        $imgUrl = str_replace("&amp;", "&", $imgUrl);

        //http开头验证
        if (strpos($imgUrl, "http") !== 0) {
            $this->stateInfo = "ERROR_HTTP_LINK";
            return;
        }

        preg_match('/(^https*:\/\/[^:\/]+)/', $imgUrl, $matches);
        $host_with_protocol = count($matches) > 1 ? $matches[1] : '';

        // 判断是否是合法 url
        if (!filter_var($host_with_protocol, FILTER_VALIDATE_URL)) {
            $this->stateInfo = "INVALID_URL";
            return;
        }

        preg_match('/^https*:\/\/(.+)/', $host_with_protocol, $matches);
        $host_without_protocol = count($matches) > 1 ? $matches[1] : '';

        // 此时提取出来的可能是 ip 也有可能是域名，先获取 ip
        $ip = gethostbyname($host_without_protocol);
        // 判断是否是私有 ip
        if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            $this->stateInfo = "INVALID_IP";
            return;
        }

        //获取请求头并检测死链
        $heads = get_headers($imgUrl, 1);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            $this->stateInfo = "ERROR_DEAD_LINK";
            return;
        }
        //格式验证(扩展名验证和Content-Type验证)
        $fileType = strtolower(strrchr($imgUrl, '.'));
        if (!in_array($fileType, $this->config['allowFiles']) || !isset($heads['Content-Type']) || !stristr($heads['Content-Type'], "image")) {
            $this->stateInfo = "ERROR_HTTP_CONTENTTYPE";
            return;
        }

        //打开输出缓冲区并获取远程图片
        ob_start();
        $context = stream_context_create(
            array('http' => array(
                'follow_location' => false // don't follow redirects
            ))
        );
        readfile($imgUrl, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

        $this->oriName = $m ? $m[1]:"";
        $this->fileSize = strlen($img);
        $this->fileType = strtolower(strrchr($this->oriName, '.'));
		
		$targetFolder = $this->$outPath;
				
		if(!is_dir($targetFolder)){mkdir($targetFolder);}
		
		
        $this->fullName = rtrim($this->outPath,"/")."/".mt_rand(0,10000).$this->fileType;       

        //检查文件大小是否超出限制
        if($this->fileSize > 2048000) {
            $this->stateInfo = "ERROR_SIZE_EXCEED";
            return;
        }

        //移动文件
        if (file_put_contents($this->fullName, $img)) { //移动失败
            $this->stateInfo = "sucess";
        } else { //移动成功           
			$this->stateInfo = "ERROR_WRITE_CONTENT";
        }
		
		return $this->fullName;
	}
	
	public function getInfo(){
		return $this->stateInfo;
	}
}

?>