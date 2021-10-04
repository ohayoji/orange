(function($){
  $.SelectedMenuRowViewController = function(menu){

    //プロパティ
    this.view = $("<tr>").addClass("selected_menu").data("menu",menu).hide();
    this.menu = menu;
    this.menuDetailCellVC = new $.MenuDetailCellViewController(this.menu);

    /*==各セルを初期化===============*/
    this.createIconCell();
    this.view.append(this.menuDetailCellVC.view);
    //金額td
    this.saleCell = this.createSaleCell();

    var _this = this;
    //メニュー詳細選択時のイベント設定
    this.menuDetailCellVC.view.find("input[type=radio]").change(function(e){
      //イベントを渡す
      _this.changeMenuDetail(e);
    });
    //金額変更時のイベント設定
    this.saleCell.find("input").change(function(e){
      _this.changeSale();
      
    });
    /*============================*/

    /*==メニュー詳細、金額表示========*/
    var recMenu = $.getRecMenuFromMenuID(this.menu.menu_id);

    //receiptMenusないにあればセット
    if (recMenu) {
      
      if (recMenu.detail_id) {//詳細が登録されていれば選択
        this.menuDetailCellVC.view.find("#md_"+ recMenu.detail_id).prop("checked",true);
      }else {//詳細登録がなければデフォルト選択
        this.selectDefaultMenuDetail(true);
      }
      if (recMenu.sales) {//金額が設定されていれば表示
        this.saleCell.find("input").val(recMenu.sales);
      }

    }else {//receiptMenusないになければ初期選択
      
      this.selectDefaultMenuDetail(false);
    }
    /*============================*/
  }

  /*======メソッド==============================*/
  $.SelectedMenuRowViewController.prototype.createIconCell = function(){
    this.view.append($("<td>").append($("<img>").attr({"src":"../image/"+this.menu.on_img}).css("display","inline")));

  }
  $.SelectedMenuRowViewController.prototype.createSaleCell = function(){

    var salebox = $("<div>").addClass("menu_salebox").text(CURRENCY + " ")
						.append($("<input>").addClass("faint not_unique_char only_num chkcode").attr({"type":"number"}).setTextStrCheck());

    var td = $("<td>").addClass("sale_cell").append(salebox);
    this.view.append(td);

    return td;
  }

  //ラヂオボタンが変更時に呼び出されるメソッド
  $.SelectedMenuRowViewController.prototype.changeMenuDetail = function(e){
    

    //ターゲット(input)
    var input = $(e.target);
    //メニュー詳細データ
    var md = $(e.target).data("mdData");

    this.saleCell.find("input").val(md.price);

    //receiptMenusを更新
    var recMenu = $.getRecMenuFromMenuID(this.menu.menu_id);
    recMenu.detail_id = md.id;

    //金額変更
    this.changeSale();
    
  }

  //金額変更じに呼び出されるメソッド
  $.SelectedMenuRowViewController.prototype.changeSale = function(){
    

    //receiptMenusを更新
    var recMenu = $.getRecMenuFromMenuID(this.menu.menu_id);
    recMenu.sales = this.saleCell.find("input").val();
  }


  //メニューデフォルトの詳細を選択
  $.SelectedMenuRowViewController.prototype.selectDefaultMenuDetail = function(existReceiptMenus){

    var len = this.menuDetailCellVC.menuDetails.length;
    for (var i = 0; i < len; i++) {
      var md = this.menuDetailCellVC.menuDetails[i];
      if (md.selected == 1) {
        this.menuDetailCellVC.view.find("#md_"+ md.id).prop("checked",true);
        this.saleCell.find("input").val(md.price);

        //receiptMenusに含まれるメニューの場合はreceiptMenusを更新
        if (existReceiptMenus) {
          var recMenu = $.getRecMenuFromMenuID(this.menu.menu_id);
          recMenu.detail_id = md.id;
          recMenu.sales = md.price;
        }
      }
    }
  }
})(jQuery);
