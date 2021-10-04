(function($) {
  //vivoの場合に伝票項目の「学生」を「mosta」に変更する
  $.changeVivoEntryName = function(){
    //if (_salonId == 41 && _postName == "サンプルサロン") {
    if (_salonId == 4 && _postName == "vivo" || _salonId == 19 && _postName == "LOLLINGS") {

      $.each(_recEnts,function(i,v){
        
        if (v._name == "net") {
          v.local_name = "mosta";
        }
      });
    }
  }
})(jQuery);
