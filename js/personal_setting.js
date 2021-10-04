
//カラーピッカー
var colorPicker;

jQuery(function ($) {
	
	
	
	colorPicker = $("#color_picker");

	/*--項目設定-----------*/
	$("input[name=e_mail]").val(_status.e_mail);
	//$("input[name=password]").val(_status.password);
	$("input[name=color]").val(_status.color)
		.css("background-color",_status.color)
		.data("pickerVisible",false)
		.on("click",function(){
			//clickCS();
			$(this).clickCB();
		});
	
	$.setColorPicker();
	$.setUsedColor();
	/*----------------*/
	
	//サブミットボタン
	$("button[type=submit]").on("click",function(){
		return confirm("入力された内容に変更してよろしいですか？");
	});
});

(function($) {
	//カラーリスト
	$.colors = function() {
		var colors = [
		    "CadetBlue","lime","green","limegreen","DarkTurquoise",
		    "DarkCyan","olivedrab","DarkSeaGreen","CornflowerBlue",
		    "DodgerBlue","RoyalBlue","skyblue","DarkBlue","midnightblue",
		    "SlateGray","Purple","mediumpurple", "mediumorchid","darkviolet",
		    "MediumVioletRed","plum","Crimson","Coral","DeepPink","DarkSalmon",
		    "DarkOrange","IndianRed ","LightCoral","OrangeRed ","Tomato",
		    "Red","GoldenRod","Maroon","Sienna","Chocolate","tan"
		    ];
		return colors;
	}
	//カラーピッカーをセット
	$.setColorPicker = function() {
		
		var colors = $.colors();
		
		$.each(colors,function(index,val){
			$("#color_picker").append(
				$("<div>")
				.attr({"id":val,"class":"color_panel"})
				.css({"background-color":val,"text-align":"center","color":"yellow"})
				.data("used",false)
				.on("click",function(){
					$(this).pickup();
				}));
		});
	}
	//使用済みカラーをセット
	$.setUsedColor = function() {
		$.each(_usedColors,function(index,val){
			
			colorPicker.find("div#"+ val.color).append("×").data("used",true);
		});
	}
	//カラーボタンクリック
	$.fn.clickCB = function() {
		//alert("cb");
		if ($(this).data("pickerVisible")) {
			
			colorPicker.hide();
			$(this).data("pickerVisible",false);

		}else {
			
			colorPicker.show();
			$(this).data("pickerVisible",true);
		}
	}
	//カラーパネルクリック
	$.fn.pickup = function() {
		var color = $(this).prop("id");
		var used = $(this).data("used");
		
		
		if (used) {
			if (!confirm("その色は他のスタッフが使用していますが、使用しますか？")) {
				return false;
			}
		}
		
		$("input[name=color]").text(color).val(color)
			.css("background-color",color);
		
	}
}(jQuery));