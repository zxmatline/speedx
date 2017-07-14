<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">
        <link rel="stylesheet" href="./dist/remodal-default-theme.css"/>        
        <link rel="stylesheet" href="./dist/remodal.css"/>
        
		<style type="text/css">	
			.websize{
				width: 1400px;
			}
			.autoCenter{
				margin: 0 auto;
			}
			

			.remodal-overlay.with-red-theme {
			  background-color: #f44336;
			}

			.remodal.with-red-theme {
			  background: #fff;
			}
		</style>
       
		
		<title>测试网页</title>				
	</head>		
				
	<body>
		<div class="websize autoCenter remodal-bg">
			<a href="#modal2"><img src="./main.jpg"></a>			
		</div> 
   	 	
  <div class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">
  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
  <div>
    <h2 id="modal1Title">提示</h2>
    <p id="modal1Desc">
      安徽杰博教育官方网站正在全新改版升中，暂时不能访问，警请谅解。
    </p>
  </div>
  <br>
  <button data-remodal-action="cancel" class="remodal-cancel">取消</button>
  <button data-remodal-action="confirm" class="remodal-confirm">确定</button>
</div>

<div data-remodal-id="modal2" role="dialog" aria-labelledby="modal2Title" aria-describedby="modal2Desc">
  <div>
    <h2 id="modal2Title">网站升级提示</h2>
    <p id="modal2Desc">
      安徽杰博教育官方网站正在全新设计改版中，包括公众号暂时不能访问和使用，预计于本月25号开放，给您带来的不便敬请谅解.
    </p>
  </div>
  <br>
  <button data-remodal-action="confirm" class="remodal-confirm">确定</button>
</div>
        <!-- js区  --> 
		<script src="./jquery-1.12.3.min.js" ></script>
        <script src="./dist/remodal.min.js"></script>
        
        
		<script type="text/javascript" language="javascript">
			 $(document).on('opening', '.remodal', function () {
				console.log('opening');
			  });

			  $(document).on('opened', '.remodal', function () {
				console.log('opened');
			  });

			  $(document).on('closing', '.remodal', function (e) {
				console.log('closing' + (e.reason ? ', reason: ' + e.reason : ''));
			  });

			  $(document).on('closed', '.remodal', function (e) {
				console.log('closed' + (e.reason ? ', reason: ' + e.reason : ''));
			  });

			  $(document).on('confirmation', '.remodal', function () {
				console.log('confirmation');
			  });

			  $(document).on('cancellation', '.remodal', function () {
				console.log('cancellation');
			  });

			$(function(){				
				$('[data-remodal-id=modal2]').remodal({
					modifier: 'with-red-theme'
				});
			});
		</script>

	</body>		
</html>

	<?php  
 	$err = "<errorentry>\n";      
    $err .= "\t<datetime>" . $dt . "</datetime>\n";      
    $err .= "\t<errornum>" . $errno . "</errornum>\n";      
    $err .= "\t<errortype>" . $errortype[$errno] . "</errortype>\n";      
    $err .= "\t<errormsg>" . $errmsg . "</errormsg>\n";      
    $err .= "\t<scriptname>" . $filename . "</scriptname>\n";      
    $err .= "\t<scriptlinenum>" . $linenum . "</scriptlinenum>\n";      
    if (in_array($errno, $user_errors)) {          
        $err .= "\t<vartrace>" . wddx_serialize_value($vars, "Variables") . "</vartrace>\n";      
    }      
    $err .= "</errorentry>\n\n";      
?>  