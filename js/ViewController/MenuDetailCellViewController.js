(function($){
  $.MenuDetailCellViewController = function(menu){

    //プロパティ
    this.view = $("<td>").addClass("detail_cell");
    this.menu = menu;
    this.menuDetails = menu.menu_datails;

    this.init();
  }

  $.MenuDetailCellViewController.prototype.init = function(){

    var len = this.menuDetails.length;

    if (!len) {//メニュー詳細がない場合はメッセージを表示
      this.view.text("メニュー詳細未設定").css({"color":"#95a5a6"});

    }else {
      for (var i = 0; i < len; i++) {
        var md = this.menuDetails[i];
        

        //削除済み項目以外を表示
  			if (md.deleted == 0) {
          //radio
          var input = $("<input>").attr({
            "type": "radio",
            "id": "md_"+md.id,
            "data-mdid": md.id,
            "data-md_sale": md.price,
            "name": this.menu._name,
          }).data("mdData", md);
          //label
          var label = $("<label>").attr("for","md_"+md.id).text(md._name);

          //追加
          this.view.append($("<div>").attr("class","md_label").append(input).append(label));
        }
      }
    }
  }
})(jQuery);
