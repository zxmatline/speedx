<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">
		<link rel="stylesheet" href="_speedx_css/_speedx_structure_style.css" />
		<link rel="stylesheet" href="_speedx_plugins/bootstrap/bootstrap4.0.5/css/bootstrap.min.css" />
		<link rel="stylesheet" href="_speedx_plugins/bootstrap/fontawesome/css/font-awesome.min.css" />
		<style type="text/css">			
			.background{
				display: table;
				height: 100%;
				width: 100%;
				position:absolute;
				background: linear-gradient(#2fb4bd 50%,#f7f6f6 50%,#f7f6f6 100%);
				z-index: -999;
			}
			
			.centerShow{
				position:absolute;
				/**以下这种居中方式为组合用法，top和left的百分比设为50%时可现水平和垂直居中*/
				top:40px;left:50%;				
				-webkit-transform: translateX(-50%);
				-moz-transform: translateX(-50%);
				-ms-transform: translateX(-50%);
				transform: translateX(-50%);	
			}
			.cmslogo{
				width:425px;
				height: 106px;
				background-image:url("_speedx_project_files/images/speedxCMS-linktodb.png");				
			}
			.linkToDb-box{
				width:700px;
				height: auto;
				background-color: white;
				border-radius: 10px;
				box-shadow: 5px 5px 24px 5px #b4b4b4;
				padding: 30px 20px;
			}
			
			.gride-row{
				display: table;
				width: 100%;
			}
			.gride-row-cell{
				display: table-cell;				
				/*border: 1px solid gray;*/
			}
			.align-left{text-align: left;}
			.align-right{text-align: right;}
			.align-center{				
				margin: 0 auto;
			}
		</style>
        <?php
			include_once("./_speedx_config/_speedx_public_config.php");
			include_once(SYSPHPLIB."class.register.php");
			include_once(SYSCONFIG."_speedx_plugins_config.php");
			include_once(SYSDATABASE."startSession.php");
		?>		
		<title>speedx后台管理系统</title>				
	</head>		
				
	<body>
		<div class = "background"></div>
		<div class="centerShow">
			<div class="cmslogo"></div>
			<div class="linkToDb-box">
			<form class="mx-auto center">
				<h4 class="text-xs-center">连接数据库</h4><br/>				

				<div class="form-group row">						
					<label for="linkdb-host" class="col-xs-3 col-form-label text-xs-right">服务器地址</label>
				  <div class="col-xs-8">				  					
					<input type="text" class="form-control" id="linkdb-host">				  
					<div class="form-control-feedback"></div>
					<small class="form-text text-muted">请输入主机名或IP地址，本机请直接输入localhost</small>
				  </div>				  
				</div>

				<div class="form-group row">
				  <label for="linkdb-dbname" class="col-xs-3 col-form-label text-xs-right">数据库名</label>
				  <div class="col-xs-8">
					<input class="form-control" type="search" id="linkdb-dbname">
					<div class="form-control-feedback"></div>
					<small class="form-text text-muted">请输入本机的数据库名称</small>
				  </div>
				</div>

				<div class="form-group row">
				  <label for="linkdb-username" class="col-xs-3 col-form-label text-xs-right">用户名</label>
				  <div class="col-xs-8">
					<input class="form-control" type="email"  id="linkdb-username">
					<div class="form-control-feedback"></div>
					<small class="form-text text-muted">请输入数据库用户名</small>
				  </div>
				</div>

				<div class="form-group row">
				  <label for="linkdb-dbpassword" class="col-xs-3 col-form-label text-xs-right">密码</label>
				  <div class="col-xs-8">
					<input class="form-control" type="password" id="linkdb-dbpassword">
					<div class="form-control-feedback"></div>
					<small class="form-text text-muted">请输入数据库连接密码</small>
				  </div>
				</div>

				<div class="form-group row">
				  <label for="linkdb-dbpassword" class="col-xs-3 col-form-label text-xs-right">图片验证码</label>
				  <div class="col-xs-4">
					<input class="form-control" type="password" id="linkdb-dbpassword">
					<div class="form-control-feedback"></div>
					<small class="form-text text-muted">请输入图片验证码</small>
				  </div>
				  <div class="col-xs-5" style>
					 <img id="veriCodeImage" src="./_speedx_actions/kcaptcha/index.php" style="cursor: pointer;"/>
					 <small class="form-text text-muted">看不清楚？单击图片可以刷新</small>
				  </div>
				</div>

				<div class="center">
					<button class="btn btn-primary">连接数据库</button>
				</div>					
			</form>	
				
				
			</div>
		</div>
		
		
		<script src="_speedx_plugins/jquery/jquery-3.0.0.min.js" ></script>
		<script src="_speedx_plugins/bootstrap/bootstrap4.0.5/js/bootstrap.min.js" ></script>        
		<script type="text/javascript" language="javascript">
			$(function(){
				$("#veriCodeImage").click(function(e){
					this.src = this.src + "?newtime="+new Date().getTime();
				})
			});
		</script>

	</body>		
</html>