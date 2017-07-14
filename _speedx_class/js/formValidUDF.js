// JavaScript Document
;(function($){
	$.fn.extend({
		"create_input_ele":function($type,$udftitle){			
			var postFile = "formValid.php";	
			var $inputDiv = $("<div></div>");		
			var $input_textNode = $("<input />");
			var $noticeImgNode = $("<img src='../../Speed备份/publicJSLib/jquery_use_lib/imgs/normal2.gif' style='vertical-align:middle' />");			
			var $noticeNode = $('<span></span>');
			$noticeNode.text($udftitle);
			
			if($type == 6){
				$input_textNode.attr("type","password");
			}else{
				$input_textNode.attr("type","text");
			}						
			$inputDiv.append($input_textNode).append($noticeImgNode).append($noticeNode);		
			
			var reg_name = /^\w{4,30}$/;
			var reg_shouji = /^\d{11}$/;
			var reg_email = /^\w{1,20}(?:@(?!-))(?:(?:[a-z0-9-]*)(?:[a-z0-9](?!-))(?:\.(?!-)))+[a-z]{2,4}$/;		
			var reg_date =  /^[0-9]{4}-[0-1]?[0-9]{1}-[0-3]?[0-9]{1}$/;
			var reg_password = /^[\w#\$%\*@]{6,30}$/;
			var reg_alias = /^[^\s\.\\\<\>]{2,20}$/i;
						
			var $lFlag = true;		
		
			$(this).append($inputDiv);
						
			$input_textNode.blur(function(){
				var temp = $input_textNode.val().replace(/\s/g,'');			
				$input_textNode.val(temp);
				$inputDiv.attr("value",$input_textNode.val());
				switch($type){
				case 1 :
					$lFlag = reg_name.test(temp) ? true : (reg_email.test(temp) ? true : (reg_shouji.test(temp) ? true : false));
					$input_textNode.attr("name","zxm_userName");					
					break;
				case 2 :
					$lFlag = reg_alias.test(temp);
					$input_textNode.attr("name","zxm_aliasName");					
					break;
				case 3 :
					$lFlag = reg_email.test(temp);
					$input_textNode.attr("name","zxm_email");					
					break;
				case 4 :
					$lFlag = reg_shouji.test(temp);
					$input_textNode.attr("name","zxm_callNumber");				
					break;	
				case 5:
					$lFlag = reg_date.test(temp);
					$input_textNode.attr("name","zxm_date");					
					break;
				case 6 :
					$lFlag = reg_password.test(temp);
					$input_textNode.attr("name","zxm_password");					
					break;				
				}		
								
				if($.trim(temp) == ""){
					$noticeImgNode.attr("src","jquery_use_lib/imgs/normal2.gif");
					$noticeNode.text($udftitle);
				}else{													
					if($lFlag){						
						$.ajax({
							url:postFile,
							type:"POST",
							dataType:"html",
							data:{value:encodeURI(temp),name:$input_textNode.attr('name')},
							beforeSend: function(XMLRequest){
								$noticeImgNode.attr("src","jquery_use_lib/imgs/loading.gif");
								$noticeNode.text("正在验证数据...");
							},
							success: function(HTML){
								HTML = decodeURI(HTML);														
								if(HTML == "RequestYes"){
									$noticeImgNode.attr("src","jquery_use_lib/imgs/request.gif");
									$noticeNode.text("可以使用");
								}else{
									$noticeImgNode.attr("src","jquery_use_lib/imgs/err.gif");
									$noticeNode.text(HTML);									
								}							
							}
						});				
					}else{
						$noticeImgNode.attr("src","jquery_use_lib/imgs/err.gif");
						$noticeNode.text("格式不正确或包含非法字符");										
					}								
				}				
			}).focus(function(){
				$(this).select();
				$noticeNode.css("color","black");
			});
			
			return $inputDiv;
		},
		
		"create_sex_ele":function(){						
			var postFile = "formValid.php";	
			var $inputDiv = $("<div></div>");
			var $innerDiv1 = $("<span></span>");
			var $innerDiv2 = $("<span></span>");		
			var input_textNode1 = $("<input type='radio' name ='sexName' style='vertical-align:middle' />");
			input_textNode1.attr("value","男");
			var input_textNode2 = input_textNode1.clone();
			input_textNode2.attr("value","女");			
			
			var noticeImgNode = $("<img src='../../Speed备份/publicJSLib/jquery_use_lib/imgs/normal2.gif' style='vertical-align:middle' />");
			var noticeNode = $('<span>请选择性别！</span>');
			
			var titleNode1 = $("<span style='vertical-align:middle'> 男 </span>");
			var spaceSplice = $("<span>  ｜  </span>");
			var titleNode2 = $("<span style='vertical-align:middle'> 女 </span>");				
			
			$innerDiv1.append(input_textNode1).append(titleNode1).append(spaceSplice).append(input_textNode2).append(titleNode2);					
			$innerDiv2.append(noticeImgNode).append(noticeNode);
			
			$inputDiv.append($innerDiv1).append($innerDiv2);			
			$inputDiv.attr("type",7);
			
			$(this).append($inputDiv);
			return 	$inputDiv;		
		},
				
		
		"create_check_ele":function(){
			var postFile = "formValid.php";	
				
			var outDiv = $("<div></div>");
			var innerDiv1 = $("<div></div>");
			var innerDiv2 = $("<div></div>");			
					
			var checkImg = $("<img />");
			checkImg.attr("src","checkUniqueCodeTest.php");				
			var checkImgTitle = $("<span>  看不清楚？换一张</span>");
			
			checkImgTitle.css({"vertical-align":"middle","color":"blue"});			
			
			checkImgTitle.hover(function(){
					$(this).css({"color":"red","cursor":"pointer"});
				},function(){
					$(this).css({"color":"blue"});
			});		
			checkImgTitle.click(function(){
				checkImg.attr("src",checkImg.attr("src") + '?nowtime=' + new Date().getTime());					
			});
				
			
			innerDiv1.append(checkImg).append(checkImgTitle);
			
			var input_textNode = $("<input type= 'text' maxlength='20' name='zxm_checkCode'/>");
			var noticeImgNode = $("<img src='../../Speed备份/publicJSLib/jquery_use_lib/imgs/normal2.gif' style='vertical-align:middle' />");			
			var noticeNode = $('<span></span>');
			
			innerDiv2.append(input_textNode).append(noticeImgNode).append(noticeNode);			
			outDiv.append(innerDiv1).append(innerDiv2);					
			$(this).append(outDiv);
			
			outDiv.attr("type",8);
			
			input_textNode.blur(function(){
				var temp = input_textNode.val().replace(/\s/g,'');
				var $lFlag = true;
				var reg_Valid = /^[a-zA-Z0-9]+$/;			
				input_textNode.val(temp);
				if($.trim(temp) == ""){
						noticeImgNode.attr("src","jquery_use_lib/imgs/normal2.gif");
						noticeNode.text("");
				}else{	
					$lFlag = reg_Valid.test(temp);							
					if($lFlag){						
						$.ajax({
							url:postFile,
							type:"POST",
							dataType:"html",
							data:{value:encodeURI(temp),name:input_textNode.attr("name")},
							beforeSend: function(XMLRequest){
								noticeImgNode.attr("src","jquery_use_lib/imgs/loading.gif");
								noticeNode.text("正在验证数据...");
							},
							success: function(HTML){
								HTML = decodeURI(HTML);														
								if(HTML == "RequestYes"){
									noticeImgNode.attr("src","jquery_use_lib/imgs/request.gif");
									noticeNode.text("验证码正确！");
								}else{
									noticeImgNode.attr("src","jquery_use_lib/imgs/err.gif");
									noticeNode.text(HTML);										
								}							
							}
						});				
					}else{
						noticeImgNode.attr("src","jquery_use_lib/imgs/err.gif");
						noticeNode.text("格式不正确或包含非法字符");										
					}								
				}				
			}).focus(function(){
				$(this).select();
				noticeNode.css("color","black");
			});
			
			return outDiv;
		},		
		
		"create_date_ele":function(){	
			var weekCreate = function($year,$month,$date){
				//w=y+[y/4]+[c/4]-2c+[26(m+1)/10]+d-1 根据蔡勒公式计算；
				if($month == 1){$month = 13; $year = $year - 1;}
				if($month == 2){$month = 14; $year = $year - 1;}
				var y = parseInt($year.toString().substr(2,2));				
				var c = parseInt($year.toString().substr(0,2));								
				var m = $month;
				var d = $date;
				var d1 = Math.floor(y/4);				
				var d2 = Math.floor(c/4);				
				var d3 = Math.floor(26*(m+1)/10); 				
				w = y + d1 + d2 - 2*c + d3 + d -1;
				
				var week = -1;
				
				if(w < 0){
					week = (Math.floor(Math.abs(w)/7)+1) * 7 - Math.abs(w);
				}else{
					week = w % 7;
				}
				
				switch(week){
				case 0:
					return "星期日";
					break;
				case 1:
					return "星期一";
					break;
				case 2:
					return "星期二";
					break;
				case 3:
					return "星期三";
					break;
				case 4:
					return "星期四";
					break;
				case 5:
					return "星期五";
					break;
				case 6:
					return "星期六";
					break;
				}				
			};
			
			var dateCreate = function($minNumber,$maxNumber,$curPointer,$orign,$li,$selectDateIndex){				
				$curPointer = ($maxNumber - $curPointer < 0) ? $maxNumber : $curPointer;
				$curPointer = ($minNumber - $curPointer > 0) ? $minNumber : $curPointer;
				
				if($orign){					
					$maxNumber = ($maxNumber - $curPointer >= 6) ? $curPointer + 6 : $maxNumber;	
					$li.each(function(index, element){
						$(this).text($maxNumber-index);															
					});		
				}else{
					$minNumber =  ($curPointer - $minNumber >= 6) ? $curPointer - 6 : $minNumber;
					$li.each(function(index,ele){
						$(this).text($minNumber + 6 - index );					
					});
				}
				
				$li.each(function(index,ele){
					if($(this).text() == selectDate[$selectDateIndex]){
						$(this).css({"color":"white","background-color":"#006699"});						
					}else{
						$(this).css({"color":"black","background-color":"white"});
					}	
				});
				
				date_tr5.find("span").eq(0).text(weekCreate(selectDate[0],selectDate[1],selectDate[2]));
				
				$li.click(function(){
					var runYueDays = runYear(selectDate[0],selectDate[1]);
					selectDate[$selectDateIndex] = parseInt($(this).text());
					selectDate[2] = (selectDate[2] > runYueDays) ? runYueDays : selectDate[2];					
					var dayMaxTep = parseInt(day_li.eq(0).text()) > runYueDays ? runYueDays : parseInt(day_li.eq(0).text());					
					day_li.each(function(index, element){
						$(this).text(dayMaxTep-index);						
						if($(this).text() == selectDate[2]){
							$(this).css({"color":"white","background-color":"#006699"});							
						}else{
							$(this).css({"color":"black","background-color":"white"});
						}										
					});						
					$(this).css({"color":"white","background-color":"#006699"});
					$li.not(this).css({"color":"black","background-color":"white"});					
					date_tr5.find("span").eq(0).text(weekCreate(selectDate[0],selectDate[1],selectDate[2]));					
				}).hover(function(){					
					$(this).css({
						"color":"black",
						"background-color":"#d8d8d8",
						"cursor":"pointer"						
					});
				},
				function(){
					if($(this).text() == selectDate[$selectDateIndex]){
						$(this).css({"color":"white","background-color":"#006699"});
					}else{
						$(this).css({
							"color":"black",
							"background-color":"white"	
						})
					}
				});	
			};
			
			var runYear = function($year,$month){
				var runNian = false;
				if($year%100==0 && $year%400==0){
					runNian = true;
				}else{
					if($year % 4 == 0){runNian = true;}
				}
				
				switch($month){
				case 1:
					return 31;
					break;
				case 2:
					if(runNian){return 29}else{return 28}
					break; 
				case 3:
					return 31;
					break; 
				case 4:
					return 30;
					break; 
				case 5:
					return 31;
					break; 
				case 6:
					return 30;
					break; 
				case 7:
					return 31;
					break; 	
				case 8:
					return 31;
					break; 
				case 9:
					return 30;
					break; 
				case 10:
					return 31;
					break; 
				case 11:
					return 30;
					break; 
				case 12:
					return 31;
					break; 
				}
					
			};
					
			var curDate = new Array(3); //今天
			var selectDate = new Array(3);//选择的日期		
			
			var tmpD = new Date();
			curDate[0] = tmpD.getFullYear();
			curDate[1] = tmpD.getMonth()+1;
			curDate[2] = tmpD.getDate();		
			
			selectDate[0] = curDate[0];
			selectDate[1] = curDate[1];
			selectDate[2] = curDate[2];						
			
			var date_Div = $("<div></div>");
			date_Div.addClass("date_outBorder");			
			date_Div.attr("type",9);
			
			var date_Input = $("<input type='text' name='zxm_udfDate'/>");			
			date_Input.addClass("date_input");
			//date_Input.attr("disabled","disabled");
			date_Input.attr("readonly","readonly");			
			var date_Img = $("<img />");			
			date_Img.attr("src","jquery_use_lib/imgs/dateImg.png");			
			date_Div.append(date_Input).append(date_Img);
			date_Img.addClass("date_icon");
			//date_Img.css({"display":"block","left":(date_Input.width - $(this).width()) + "px"});		
			
			
			var date_table = $("<table></table>");							
			var date_tr1 = $("<tr><th>年</th><th>月</th><th>日</th></tr>");
			date_tr1.css({"background-color":"#060","color":"#FF9"});
			date_tr1.children("th").addClass("date_head_th");								
			var date_tr2 = $("<tr><td>▲</td><td>▲</td><td>▲</td></tr>");			
			date_tr2.children("td").addClass("date_upAndDown");			
			var date_tr3 = $("<tr><td></td><td></td><td></td></tr>");
			date_tr3.children("td").addClass("date_containt");	
						
			var date_tr4 = $("<tr><td>▼</td><td>▼</td><td>▼</td></tr>");	
			date_tr4.children("td").addClass("date_upAndDown");				
			var date_tr5 = $("<tr><td colspan='3'><span>星期</span> <span>今天</span> <span>取消</span> <span>清除</span> <span>确定</span></td></tr>");
			
			date_tr5.find("td").css({"padding":"3px","height":"22px","text-align":"center","background-color":"#FFF"});
			date_tr5.find("span").addClass("footerShow");
						
			date_table.append(date_tr1).append(date_tr2).append(date_tr3).append(date_tr4).append(date_tr5);
			date_table.addClass("date_table");
			
			$(this).append(date_Div).append(date_table);			
			
			var date_Year_ul = $("<ul><li></li><li></li><li></li><li></li><li></li><li></li><li></li></ul>");
			
			date_Year_ul.addClass("date_containt_ul");
			
			var date_Month_ul = date_Year_ul.clone();
			var date_Day_ul = date_Year_ul.clone();	
			
			date_tr3.children("td").each(function(index, element) {
                if(index == 0){$(this).append(date_Year_ul);}
				if(index == 1){$(this).append(date_Month_ul);}
				if(index == 2){$(this).append(date_Day_ul);}
            });	
			
			var year_li = date_Year_ul.children("li");	
			var month_li = date_Month_ul.children("li");
			var day_li = date_Day_ul.children("li");			
			
			dateCreate(1900,curDate[0],selectDate[0],true,year_li,0);
			dateCreate(1,12,selectDate[1],true,month_li,1);
			dateCreate(1,runYear(curDate[0],curDate[1]),selectDate[2],true,day_li,2);	
			
			var nLeft = date_Div.offset().left+"px";
			var nTop =  (date_Div.offset().top + date_Div.height() + 5) +"px";			
			date_table.offset({left:nLeft,top:nTop});
			
			date_Div.width(date_Input.width());	
			
			var imgLeft = date_Div.offset().left + date_Input.width()-25;				
			var imgTop = date_Input.offset().top + 4;
			
			date_Img.offset({left:imgLeft,top:imgTop});			
			date_table.hide();
			
			var date_span = date_tr5.find("span");
			date_span.eq(1).click(function(){
				selectDate[0] = curDate[0];
				selectDate[1] = curDate[1];
				selectDate[2] = curDate[2];	
				
				dateCreate(1900,curDate[0],selectDate[0],true,year_li,0);
				dateCreate(1,12,selectDate[1],true,month_li,1);
				dateCreate(1,runYear(curDate[0],curDate[1]),selectDate[2],true,day_li,2);		
			});		
			
			date_span.eq(2).click(function(){
				date_table.hide(500);
			});	
			
			date_span.eq(3).click(function(){
				date_Input.val("");
			});	
			
			date_span.eq(4).click(function(){			
				date_Input.val(selectDate[0] + "-" + selectDate[1] + "-" + selectDate[2]);
				date_table.hide(500);
			});
			
			date_Img.click(function(){		
				date_table.show(500);				
				dateCreate(1900,curDate[0],selectDate[0],true,year_li,0);
				dateCreate(1,12,selectDate[1],true,month_li,1);
				dateCreate(1,runYear(curDate[0],curDate[1]),selectDate[2],true,day_li,2);
				
			})
			
			
			date_tr2.find("td").eq(0).click(function(){
               dateCreate(1900,curDate[0],parseInt(year_li.eq(0).text()),true,year_li,0);
            });
			
			date_tr2.find("td").eq(1).click(function(){
               dateCreate(1,12,parseInt(month_li.eq(0).text()),true,month_li,1);
            });
			
			date_tr2.find("td").eq(2).click(function(){
               dateCreate(1,runYear(selectDate[0],selectDate[1]),parseInt(day_li.eq(0).text()),true,day_li,2);
            });
			
			date_tr4.find("td").eq(0).click(function(){
               dateCreate(1900,curDate[0],parseInt(year_li.eq(6).text()),false,year_li,0);
            });
			
			date_tr4.find("td").eq(1).click(function(){
               dateCreate(1,12,parseInt(month_li.eq(6).text()),false,month_li,1);
            });
			
			date_tr4.find("td").eq(2).click(function(){
               dateCreate(1,runYear(selectDate[0],selectDate[1]),parseInt(day_li.eq(6).text()),false,day_li,2);
            });
			
			return  date_Div;		
		},
		
		"create_password_ele":function(){
			var $div = $("<div></div>");
			var $input = $("<input type='password' name='zxm_udfPassword'/>");			
			$input.attr("maxlength","40");
			var $state_img = $("<img src='../../Speed备份/publicJSLib/jquery_use_lib/imgs/normal2.gif' />");
			var $state = $("<span></span>");
			var $udfTitle = "密码6～30个字符！";
			$state.text($udfTitle);			
			$div.append($input).append($state_img).append($state);			
			
			$div.attr("type",10);
			
			$div.css({"border":"none","vertical-align":"middle"});	
			$state_img.css({"vertical-align":"middle"});
			
			$(this).append($div);
			
			var reg_password = /^[\w#\$%\*@]{6,30}$/;
			
			var $smark = $("<div></div>");			
			$smark.width(window.screen.width);
			$smark.height(window.screen.height);
			$smark.css({"position":"fixed","background-color":"black","opacity":"0.5"});
			$smark.offset({left:0,top:0});
		
			var $innerDiv = $("<div></div>");
			
			var $innerTable = $("<table></table>");			
			var $innerTr1 = $("<tr></tr>");
			var $inner_tr1_td1 = $("<td></td>");
			var $inner_tr1_td2 = $("<td></td>");			
			$innerTr1.append($inner_tr1_td1).append($inner_tr1_td2);
		
				
			var $innerTr2 = $("<tr></tr>");
			var $inner_tr2_td1 = $("<td></td>");
			var $inner_tr2_td2 = $("<td></td>");
			$innerTr2.append($inner_tr2_td1).append($inner_tr2_td2);			
			
			
			var $innerInputTitle1 = $("<span>密码：</span>");
			var $innerInput1 = $input.clone();
			$inner_tr1_td1.append($innerInputTitle1);
			$inner_tr1_td2.append($innerInput1);
			
			
			var $innerInputTitle2 = $("<span>重复一遍密码：</span>");
			var $innerInput2 = $input.clone();
			$inner_tr2_td1.append($innerInputTitle2);
			$inner_tr2_td2.append($innerInput2);
			
			var $innerStateDiv = $("<div></div>");			
			var $innerStateImg = $("<img src='../../Speed备份/publicJSLib/jquery_use_lib/imgs/normal2.gif' />");
			var $innerStateTitle = $("<span>请确认两次输入的密码一致</span>");
			$innerStateDiv.append($innerStateImg).append($innerStateTitle);			
			
			
			var $innerButtonDiv = $("<div></div>");
			var $innerButton1 = $("<input type='button' value='取消'/>");
			var $innerButton2 = $("<input type='button' value='确定'/>");			
			$innerButtonDiv.append($innerButton1).append($innerButton2);			
			
			
			$innerStateDiv.css({"vertical-align":"middle","text-align":"center","color":"red"});
			$innerStateImg.css({"vertical-align":"middle"});
				$innerTable.css({
				"border-collapse":"collaps",
				"border":"none",
				"padding":"0px",
				"margin":"0 auto"
			});			
			
			$innerTable.append($innerTr1).append($innerTr2);	
			
			$inner_tr1_td1.css({"text-align":"right"});
			$inner_tr1_td2.css({"text-align":"right"});
			
			$innerDiv.css({
				"position":"absolute",
				"width":"300px",
				"padding":"20px",				
				"border":"1px solid black",
				"background-color":"white"
			});			
						
			$innerDiv.append($innerTable).append($innerStateDiv).append($innerButtonDiv);
						
			$("body").append($smark).append($innerDiv);		
						
			$smark.hide();
			$innerDiv.hide();
			$innerButtonDiv.css({"text-align":"right"});		
								
			
			$input.blur(function(){											
				var thisVal = $input.val().replace(/\s/g,'');				
				$input.val(thisVal);
				$innerInput2.val("");						
				if($.trim(thisVal) == ""){
					$state_img.attr("src","jquery_use_lib/imgs/normal2.gif");
					$state.text($udfTitle);	
				}else{
					if(reg_password.test(thisVal)){
						$state_img.attr("src","jquery_use_lib/imgs/request.gif");
						$state.text("");
						$smark.fadeIn(300);
						$innerDiv.show(300);
						
						//var nLeft = $div.offset().left;			
						//var nTop = $div.offset().top + 25;
						//$innerDiv.offset({left:nLeft,top:nTop});
						$innerDiv.css({"top":"30%","left":"40%"});
										
						$innerInput1.val(thisVal);
						$innerInput2.select();						
					}else{
						$state_img.attr("src","jquery_use_lib/imgs/err.gif");
						$state.text("格式不正确或包含非法字符");
						$input.select();						
					}
				}				
			});
			
			$innerButton2.click(function(){
				var innerVal1 = $innerInput1.val().replace(/\s/g,'');				
				var innerVal2 = $innerInput2.val().replace(/\s/g,'');
				
				if(!reg_password.test(innerVal1) || !reg_password.test(innerVal2)){
					$innerStateImg.attr("src","jquery_use_lib/imgs/err.gif");
					$innerStateTitle.text("格式不正确或包含非法字符");					
				}else if(innerVal1 != innerVal2){
					$innerStateImg.attr("src","jquery_use_lib/imgs/err.gif");
					$innerStateTitle.text("两次输入的密码不一致，请重新输入!!");					
				}else{
					$innerStateImg.attr("src","jquery_use_lib/imgs/normal2.gif");
					$innerStateTitle.text("请确认两次输入的密码一致");
					$input.val(innerVal1);
					$smark.hide();
					$innerDiv.hide(300);					
				}			
			});
			
			$innerButton1.click(function(){
				$state_img.attr("src","jquery_use_lib/imgs/normal2.gif");
				$state.text($udfTitle);	
				$input.val("");
				$smark.hide();
				$innerDiv.hide(300);
			});			
			
			return  $div;						
		},
		
		"getInput":function(){			
			return $(this).find("input");				
		},
		
		"getSexValue":function(){
			var $value = "nu" ;
			$(this).find("input").each(function(index, element) {
                if($(this).is(":checked")){$value = $(this).val();}
            });
			return $value;
		},
		
		"outputInfo":function($info){
			var $smark = $("<div></div>");
			var $innerDiv = $("<div></div>");
			var $innerInfo = $("<div></div>");
			var $close = $("<div>关闭</div>");
			$close.css({"width":"50px","height":"20px","float":"right","line-height":"20px","text-align":"center","vertical-align":"middle"});
			$close.hover(function(){
				$(this).css({"background-color":"red","color":"white","cursor":"pointer"});
				},function(){
				$(this).css({"background-color":"white","color":"black"});
			});
			$innerInfo.append($info);				
			
			$smark.width(window.screen.width);
			$smark.height(window.screen.height);
			$smark.css({"position":"fixed","background-color":"black","opacity":"0.5"});
			$smark.offset({left:0,top:0});		
			
			$innerDiv.css({
				"position":"absolute",
				"width":"350px",
				"height":"150px",
				"padding":"5px",				
				"border":"1px solid black",
				"background-color":"white",
				"top":"30%",
				"left":"35%"
				
			});		
			
			$innerInfo.css({"width":"300px","position":"relative","top":"50px","left":"20px"});
			
			//var nLeft = Math.floor((window.outerWidth - $innerDiv.width()) / 2);								
			//var nTop = Math.floor((window.outerHeight - $innerDiv.height()) / 2);
			
			//$innerDiv.offset({left:nLeft,top:200});
			
			$innerDiv.append($close).append($innerInfo);
			$("body").append($smark).append($innerDiv);	
			
			$close.click(function(){
				$smark.remove();
				$innerDiv.remove();
			});			
			//alert(window.screen.width);
					
		}		
	})
})(jQuery);