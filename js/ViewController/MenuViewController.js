//伝票画面の選択済みメニュービューを操作し_receipt.menusと同期させる

var menus;//メニュー一覧
var receiptMenus;//_receipt.menus

/*--view---------------*/
var menuListView;//メニューリスト
var selectedMenuListView;//選択済みメニューテーブル
/*---------------------*/

jQuery(function ($) {

  menus = _menus;
  //_receipt.menusを複製
  receiptMenus = $.extend(true,[],_receipt.menus);
  
  
  


  menuListView = {

    view: $("#menu_list"),

    init: function(){
      $.each(menus,function(index,menu){

        //メニューアイコン
        var menuIcon = $.createMenuIcon(menu,{boxW:40});

    		menuListView.view.append(
          menuIcon.addClass("receipt").on("click",function(){
            //選択済みメニューリストと連動させる
            var tr = selectedMenuListView.view.find("tr:eq("+ index +")").toggle();

            //receiptMenusのデータを更新
            if (menuIcon.find("img:visible").prop("class") == "on") {

              var recMenu = $.addRecMenu(menu);
              //メニュー詳細を選択
              selectedMenuListView.selectedMenuRowVCs[index].selectDefaultMenuDetail();
              //tr.selectMenuDetail(recMenu);

  					}else {

              $.deleteRecMenu(menu);
              //メニュー詳細をリセット
              tr.find("td.detail_cell input").prop("checked",false);
              //金額をリセット
              tr.find(".menu_salebox input").val(null);
              
  					}
            
          })
        );

        //無効メニューは選択できないように非表示にする
        if (!menu.um_id) {
          menuIcon.hide();
        }
  	  });
      //一旦全て非選択に
      menuListView.view.find("img").toggle();
    },
    defaultSelect: function(){//メニューを初期選択
      $.each(receiptMenus,function(index,recMenu){
        var menuID = recMenu.menu_id;
        var menuIcon = menuListView.view.find("div.menu_icon[title="+ menuID +"]");

        //選択
        menuIcon.find("img").toggle();
        selectedMenuListView.view.find("tr:eq("+ menuIcon.index() +")").show();
      });
    }
  };


  selectedMenuListView = {

    view: $("#selected_menus"),

    selectedMenuRowVCs: [],

    init: function(){
      $.each(menus,function(index,val){

        var row = new $.SelectedMenuRowViewController(val);

        selectedMenuListView.view.append(row.view);
        selectedMenuListView.selectedMenuRowVCs.push(row);
      });
    },

  }


  menuListView.init();

  selectedMenuListView.init();

  menuListView.defaultSelect();
});

(function($) {
  //menu_idからrecMenuを取得
  $.getRecMenuFromMenuID = function(menuID){
    for (var i = 0; i < receiptMenus.length; i++) {
      var recMenu = receiptMenus[i];
      if (recMenu.menu_id == menuID) {
        return recMenu;
      }
    }
  }

  /*--receiptMenus操作メソッド---------------*/
  $.addRecMenu = function(menu){
    //作成
    var newRecMenu = {
      detail_id: $.getDefaultSelectedMenuDetailID(menu),
      id: null,
      menu_id: menu.menu_id,
      sales: null,
    }

    //追加
    receiptMenus.push(newRecMenu);

    return receiptMenus;
  }
  $.getDefaultSelectedMenuDetailID = function(menu){
    var menuDetails = menu.menu_datails;
    for (var i = 0; i < menuDetails.length; i++) {
      if (menuDetails[i].selected == 1) {
        return menuDetails[i].id;
      }
    }
    return null;
  }
  $.deleteRecMenu = function(menu){
    for (var i = 0; i < receiptMenus.length; i++) {
      if (receiptMenus[i].menu_id == menu.menu_id) {
        receiptMenus.splice(i, 1);
        return receiptMenus;
      }
    }
    return receiptMenus;
  }
  /*---------------------------------------*/

}(jQuery));
