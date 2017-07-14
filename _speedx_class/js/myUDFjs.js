// JavaScript Document

/*图片轮播器*/
;(function($){
	$.fn.extend({
		//各种运动算法集合//
	algorithm:{ //t：当前时间；c：总变化量;d：持续多长时间;b:起始变化值; 
		Linear: function(t,b,c,d){ 
				return c*t/d + b; 
		},	
		Quad: {
			easeIn: function(t,b,c,d){
				return c*(t/=d)*t + b;
			},
			easeOut: function(t,b,c,d){
				return -c *(t/=d)*(t-2) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return c/2*t*t + b;
				return -c/2 * ((--t)*(t-2) - 1) + b;
			}
		},
		Cubic: {
			easeIn: function(t,b,c,d){
				return c*(t/=d)*t*t + b;
			},
			easeOut: function(t,b,c,d){
				return c*((t=t/d-1)*t*t + 1) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return c/2*t*t*t + b;
				return c/2*((t-=2)*t*t + 2) + b;
			}
		},
		Quart: { 
			easeIn: function(t,b,c,d){
				return c*(t/=d)*t*t*t + b;
			},
			easeOut: function(t,b,c,d){
				return -c * ((t=t/d-1)*t*t*t - 1) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
				return -c/2 * ((t-=2)*t*t*t - 2) + b;
			}
		},
		Quint: {
			easeIn: function(t,b,c,d){
				return c*(t/=d)*t*t*t*t + b;
			},
			easeOut: function(t,b,c,d){
				return c*((t=t/d-1)*t*t*t*t + 1) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
				return c/2*((t-=2)*t*t*t*t + 2) + b;
			}
		},
		Sine: {
			easeIn: function(t,b,c,d){
				return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
			},
			easeOut: function(t,b,c,d){
				return c * Math.sin(t/d * (Math.PI/2)) + b;
			},
			easeInOut: function(t,b,c,d){
				return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
			}
		},
		Expo: {
			easeIn: function(t,b,c,d){
				return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
			},
			easeOut: function(t,b,c,d){
				return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
			},
			easeInOut: function(t,b,c,d){
				if (t==0) return b;
				if (t==d) return b+c;
				if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
				return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
			}
		},
		Circ: {
			easeIn: function(t,b,c,d){
				return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
			},
			easeOut: function(t,b,c,d){
				return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
			},
			easeInOut: function(t,b,c,d){
				if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
				return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
			}
		},
		Elastic: {
			easeIn: function(t,b,c,d,a,p){
				if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
				if (!a || a < Math.abs(c)) { a=c; var s=p/4; }
				else var s = p/(2*Math.PI) * Math.asin (c/a);
				return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
			},
			easeOut: function(t,b,c,d,a,p){
				if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
				if (!a || a < Math.abs(c)) { a=c; var s=p/4; }
				else var s = p/(2*Math.PI) * Math.asin (c/a);
				return (a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b);
			},
			easeInOut: function(t,b,c,d,a,p){
				if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
				if (!a || a < Math.abs(c)) { a=c; var s=p/4; }
				else var s = p/(2*Math.PI) * Math.asin (c/a);
				if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
				return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
			}
		},
		Back: {
			easeIn: function(t,b,c,d,s){
				if (s == undefined) s = 1.70158;
				return c*(t/=d)*t*((s+1)*t - s) + b;
			},
			easeOut: function(t,b,c,d,s){
				if (s == undefined) s = 1.70158;
				return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
			},
			easeInOut: function(t,b,c,d,s){
				if (s == undefined) s = 1.70158; 
				if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
				return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
			}
		},
		Bounce: {
			easeIn: function(t,b,c,d){
				return c - Tween.Bounce.easeOut(d-t, 0, c, d) + b;
			},
			easeOut: function(t,b,c,d){
				if ((t/=d) < (1/2.75)) {
					return c*(7.5625*t*t) + b;
				} else if (t < (2/2.75)) {
					return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
				} else if (t < (2.5/2.75)) {
					return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
				} else {
					return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
				}
			},
			easeInOut: function(t,b,c,d){
				if (t < d/2) return Tween.Bounce.easeIn(t*2, 0, c, d) * .5 + b;
				else return Tween.Bounce.easeOut(t*2-d, 0, c, d) * .5 + c*.5 + b;
			}
		}
	},
	
		"createImageContainter":function($imageArray){
			var $div = $("<div></div>");
			$(this).css("margin","2px 0");			
			$div.css({"padding":"0px",
					  "margin":"0px",	 				 
					  "position":"relative"					 			 				 
			});			
			
			var $ul = $("<ul></ul>");
			var $imgLen = $imageArray.length;						
			$.each($imageArray,function(index,element){
				var $img = $("<img/>");
				var $li = $("<li>" + (index+1) + "</li>");				
				$img.attr("src",element);
				$div.append($img);				
				$ul.append($li);			
			})		
			
			$imgWidth = $(this).width() * $imgLen;			
			$div.css("width",$imgWidth+"px");
			
			
			$ul.css({
					"padding":"0px",
					"margin":"0px",	 	
	 				"list-style":"none",
					"position":"absolute",
					"top":"180px",
					"left":"20px"	
			});
			
			
			$ul.children("li").css({
									"float":"left",
 	 								"padding":"5px",
	 								"margin":"0 5px",	
									"background-color":"black",
									"color":"white",
	 								"opacity":"0.5"	
			});
			
			$.each($ul.children("li"),function(index,element){
				$(this).mouseenter(function(){
					$imgWidth = $div.children("img").eq(index).width();					
					$div.stop();
					clearInterval($intervalHander);
					$div.animate({left: (0 - index*$imgWidth) + "px"},1000);
					$(this).css({"background-color":"red","opacity":"1","cursor":"pointer"});
				}).mouseout(function(){
					$(this).css({"background-color":"black","opacity":"0.5"});
					$intervalHander = $("#showImages").autoPlay();			
				});				
			});
			
			$(this).append($div).append($ul).css({
								//"border":"gray 1px solid",
								"width":"1000px",
								"height":"227px",
								"position":"relative",
								"overflow":"hidden"
								
			});		
		},
		
		"autoPlay":function(){
			var $div = $(this).children("div");			
			var $lis = $(this).find("li");			
			var $countLi = $lis.length;				
			$intervalHander = setInterval(function(){
				ab = (typeof(ab) == "undefined")? 1 : ab;
				ab = ab > $countLi-1 ? 0 :ab;				
				$div.animate({left: (0 - ab*1000) + "px"},1000);
				$.each($lis,function(index,element){
					if(index == ab){
						$(element).css({"background-color":"red","opacity":"1"});	
					}else{
						$(element).css({"background-color":"black","opacity":"0.5"});
					}
				});	
				ab += 1;
			},3000);
			return $intervalHander;
		},
		
		"currentContain":function(){
			$(this).css("position","relative");
			var $div = $("<div></div>");
			$div.css({				
				"height":"5px",
				"background-color":"red",
				"position":"absolute",
				"top":"75px"				
			});
			$(this).append($div);
			
			$.each($(this).find("td"),function(index,element){
				$div.width($(this).outerWidth());				
				$(this).mouseenter(function(){
					$(this).css("cursor","pointer");
				}).click(function(){
					$div.stop();					
					$div.animate({"left": $(this).position().left + "px"},300);
				});				
			})
		}
	})	
})(jQuery);