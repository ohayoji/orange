/**
 * 参考サイト　http://ichi.fool.jp/blog/?p=1576
 * 使い方
	var o1 = new $.overlay(); // オーバーレイを用意。 
	$('#btn01').click(function(){ //#btn01をクリックしたら～。 
    		o1.open(); //さっき用意したオーバーレイを表示させるよ。 
	}); 
	newでインスタンスつくったらオーバーレイのdivがbody最下部に非表示で挿入されます。
	で、あとはメソッド（open/close）を使っていじいじしてください。
	
----オプション（『new $.overlay()』のね。）-------

bg_color（デフォルト："#000000"）
オーバーレイの色。ご随意に。
opacity（デフォルト：0.5）
オーバーレイの透明度。0（完全に透明）から1（完全に不透明）の間で設定。
fade_speed（デフォルト：400）
fadeIn/fadeOutの所要時間。単位はミリ秒。
overlay_class（デフォルト："pageOverlay"）
オーバーレイのdivタグにつけられるクラス名＆IDのprefix。
自前のクラスとかぶって困るとかじゃない限りいじんなくていいと思う。
click_close（デフォルト：true）
true|falseか、this.closeへの引数（引数が複数なら配列で）を設定。
これがtrueか引数だと、開いたオーバーレイをクリックでそれ自身が閉じられる。
メソッド

open([speed] [, callback])
speedでfadeInのスピードを。（無ければfade_speedが適用）
callbackでfadeIn終了時の動作を。
speed無しのcallbackだけの指定もok。
close([speed] [, callback])
speedでfadeOutのスピードを。（無ければfade_speedが適用）
callbackでfadeOut終了時の動作を。
speed無しのcallbackだけの指定もok。
----------------------------------------------
 */
(function($){//BEGIN $ = jQuery
	
	//$.pageSize菴ｿ縺�ｈ縲ゅ↑縺阪ｃ縺薙％縺ｧ縲
	$.pageSize = $.pageSize || function(){
		var xScroll, yScroll;
		if(window.innerHeight && window.scrollMaxY){
			xScroll = window.innerWidth + window.scrollMaxX;
			yScroll = window.innerHeight + window.scrollMaxY;
		}else if(document.body.scrollHeight > document.body.offsetHeight){
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		}else{
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}
		
		var windowWidth, windowHeight;
		if(self.innerHeight) {
			if(document.documentElement.clientWidth){
				windowWidth = document.documentElement.clientWidth; 
			} else {
				windowWidth = self.innerWidth;
			}
			windowHeight = self.innerHeight;
		}else if(document.documentElement && document.documentElement.clientHeight){
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		}else if(document.body){
			windowWidth = document.body.clientWidth;
			windowHeight = document.body.clientHeight;
		}
		
		var pageWidth = xScroll < windowWidth ? xScroll : windowWidth;
		var pageHeight = yScroll < windowHeight ? windowHeight : yScroll;
		
		return [pageWidth,pageHeight,windowWidth,windowHeight];
	};
	
	//initialize
	$.overlay = function(settings){
		var _this = this;
		settings = $.extend({
			  bg_color: "#000000"
			, opacity: 0.5
			, fade_speed: 400
			, overlay_class: "pageOverlay"
			, click_close: true // true|false縺九》his.close縺ｸ縺ｮ蠑墓焚�亥ｼ墓焚縺瑚､�焚縺ｪ繧蛾�蛻励〒�峨
		}, settings);
		var click_close = settings.click_close;
		
		var pageSize = $.overlay.pageSize = $.pageSize();
		
		this.fade_speed = settings.fade_speed;
		this.opacity = settings.opacity;
		this.overlay_id = settings.overlay_class + $.overlay.uid++;
		this.click_close = settings.click_close;
		this.$select;
		this.opend = false;
		
		//overlay
		var $overlay = this.$obj = $('<div></div>').attr({
				  'class': settings.overlay_class
				, id: this.overlay_id
			}).css({
				  display:			"none"
				, width: pageSize[2]
				, height: pageSize[1]
				, position:			"absolute"
				, top:				0
				, left:				0
				, backgroundColor:	settings.bg_color
				, opacity:			0
			});
		$overlay.appendTo($('body'));
		$.overlay.$objs = $.overlay.$objs.add($overlay);
		
		//click_close
		if(click_close){
			$overlay.click(function(){
				if(click_close === true){
					_this.close();
				}else{
					_this.close.apply(_this, $.makeArray(click_close));
				}
			});
		}
		
		//onresize
		$(window).unbind('resize.overlay_resize').bind('resize.overlay_resize', function(){
			pageSize = $.overlay.pageSize = $.pageSize();
			$.overlay.$objs.css({
				width: pageSize[2],
				height: pageSize[1]
			});
		});
	}
	
	//uid
	$.overlay.uid = 0;
	
	//jQuery objects
	$.overlay.$objs = $([]);
	
	//pageSize
	$.overlay.pageSize = [];
	
	//prototype
	$.overlay.prototype = {
		//open
		  open: function(speed, callback){
			var _this = this;
			var $overlay = this.$obj;
			//open時にselectが消えてしまうのでコメントアウト
			//this.$select = $('select:visible').hide();
			if(!callback && $.isFunction(speed)){
				callback = speed;
				speed = null;
			}
			$overlay
				.show()
				.fadeTo(speed || this.fade_speed, this.opacity, function(){
					_this.opend = true;
					if(callback && $.isFunction(callback)) callback.apply(this, arguments);
				});
		}
		//close
		, close: function(speed, callback){
			var _this = this;
			if(_this.opend){
				var $overlay = this.$obj;
				var $select = this.$select;
				if(!callback && $.isFunction(speed)){
					callback = speed;
					speed = null;
				}
				_this.opend = false;
				$overlay
					.stop()
					.fadeTo(speed || this.fade_speed, 0, function(){
						$overlay.hide();
						//$select.show();
						if(callback && $.isFunction(callback)) callback.apply(this, arguments);
					});
			}
		}
	};
	
	
})(jQuery);//END $ = jQuery