(function($) {
  //コントローラからはこのメソッドを呼び出す
  $.occupancyRate = function(obj){
    var ocRates = $.getOccupancyRate();

    var fixedOutbizOcRate = (ocRates.outbizOcRate *100).toFixed(2);
    var fixedInbizOcRate = (ocRates.inbizOcRate *100).toFixed(2);
    //console.log("fixedOutbizOcRate", fixedOutbizOcRate);
    //console.log("fixedInbizOcRate", fixedInbizOcRate);

    var dl1 = $("<dl>").append(
      $("<dt class='f_14'>").text("営業内稼働率")
    ).append(
      $("<dd class='orange'>").text(fixedInbizOcRate + "%")
    );
    var dl2 = $("<dl>").addClass("clearfix").append(
      $("<dt class='f_14'>").text("営業外稼働率")
    ).append(
      $("<dd class='orange'>").text(fixedOutbizOcRate + "%")
    );


    obj.append(dl1).append(dl2).children().css({
      "margin": "0 2px",
      "float": "left",
      "width": "48%"
    });

  }

  $.getOccupancyRate = function(){

    var tables = $.getRserveTables();//対象のテーブル

    var outbizCells = tables.find("td.outbiz");//時間外予約枠セル
    var inbizCells = tables.find("td.inbiz");//時間内予約枠セル

    var outbizOnLen = outbizCells.not("[title=off]").length;
    var inbizOnLen = inbizCells.not("[title=off]").length;
    ////console.log("outbizOnLen",outbizOnLen);
    ////console.log("inbizOnLen",inbizOnLen);

    var outbizOcRate = outbizOnLen / outbizCells.length;
    var inbizOcRate = inbizOnLen / inbizCells.length;
    ////console.log("outbizOcRate",outbizOcRate);
    ////console.log("inbizOcRate",inbizOcRate);

    return {"outbizOcRate": outbizOcRate, "inbizOcRate": inbizOcRate};
  }

  //対象のテーブルを取得
  $.getRserveTables = function(){

    var tables;//対象のテーブル

    if (_salonId == 4) {//oro-vivo
      tables = $("table.area_reserv_table").filter("#area_5, #area_15");
    } else if (_salonId == 1) {//oro-ebisu
      tables = $("table.area_reserv_table").filter("#area_1, #area_2");
    } else {
      tables = $("table.area_reserv_table");
    }

    return tables;
  }
}(jQuery));
