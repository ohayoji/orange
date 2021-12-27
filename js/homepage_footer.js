/**
 * 
 */
jQuery(function ($) {
	
	var homepageFooter = $("<footer>").attr({
			"class":"home_contents clearfix","id":"footer"})
			.html(
				'<div class="contents_area">' +
					'<p>株式会社 turba</p>' +
					'<p class="f_small">東京都渋谷区恵比寿西1-33-15EN代官山ビル601</p>' +
			
					//'<script language="JavaScript" type="text/javascript" src="https://trusted-web-seal.cybertrust.ne.jp/seal/getScript?host_name=orange01.jp&amp;type=45&amp;svc=4&amp;cmid=2012706"></script>' +
			
					'<p id="copyright">' +
						'Copyright 2015 © turba All Rights Reserved.' +
					'</p>' +
				'</div>'	
			);
	
	//フッターコンテンツ
	var contents = $("<div>").attr("class","footer_contents contents_area");
	var navList = $("#nav1 ul").clone();
	navList.append(
			$("<li>").append(
				$("<a>").attr("href","tokutei.php").text("特定商取引法に基づく表記")));

	
	homepageFooter.prepend($("#site_label")).prepend(contents.append(navList));
	
	$("#container").append(homepageFooter);
	
	//兄要素の下パディングを設定
	homepageFooter.prev().css("padding-bottom",homepageFooter.outerHeight() + 50);
});