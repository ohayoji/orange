export class Reserve {

  area_id: number;
  staff_id: number;
  start: string;
  end: string;

  //Date型プロパティ
  date_start: Date;
  date_end: Date;
  //コマ数
  timeHeight: number;

  constructor(minitUnit: number, reserve?: any){
    if (reserve) {
      this.area_id = reserve.area_id;
      this.staff_id = reserve.staff_id;
      this.start = reserve.start;
      this.end = reserve.end;

      this.setDateProps();
      this.setTimeHeight(minitUnit);
    }
  }

  //日付プロパティ作成
  setDateProps(){
    if (!this.start || !this.end) return false;
    this.date_start = new Date(this.start);
    this.date_end = new Date(this.end);
  }
  //timeHeight作成
  setTimeHeight(minitUnit){
    //差分を計算
    var interval = this.date_end.getTime() - this.date_start.getTime();
    //分単位に変換
    var minute = interval / 60000;
    //設定されたminute分割単位で分割
    this.timeHeight = minute / minitUnit;
  }
}
