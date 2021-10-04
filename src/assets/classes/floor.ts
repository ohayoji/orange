// Floor
// エリア結合データ(フロア)を管理するクラス
interface occupancyRate {
  businesstime: number;
  notBusinesstime: number;
}

import { AreaStatus } from './area-status';
import { Area } from './area';

export class Floor extends AreaStatus {

  //表示用の値
  businesstimeOccupancyRateForDisplay: string;
  notBusinesstimeOccupancyRateForDisplay: string;

  constructor(
    private areas: Area[]
  ){
    super();

    this.setOccupancyStatus();
  }

  //稼働率計算(全エリアを合計する)
  setOccupancyStatus(): void{
    this.businesstimeSeatsTotal = 0;
    this.notBusinesstimeSeatsTotal = 0;
    this.activeSeatsInBusiness = 0;
    this.activeSeatsOutBusiness = 0;

    for (let area of this.areas) {
      //稼働率計算対象のエリア
      if (area.selectedForOccupancy) {
      this.businesstimeSeatsTotal += area.businesstimeSeatsTotal;
      this.notBusinesstimeSeatsTotal += area.notBusinesstimeSeatsTotal;
      this.activeSeatsInBusiness += area.activeSeatsInBusiness;
      this.activeSeatsOutBusiness += area.activeSeatsOutBusiness;
      }
    }

    this.businesstimeOccupancyRate = this.activeSeatsInBusiness / this.businesstimeSeatsTotal;
    this.notBusinesstimeOccupancyRate = this.activeSeatsOutBusiness / this.notBusinesstimeSeatsTotal;

    this.businesstimeOccupancyRateForDisplay = (this.businesstimeOccupancyRate *100).toFixed(2);
    this.notBusinesstimeOccupancyRateForDisplay = (this.notBusinesstimeOccupancyRate *100).toFixed(2);
  }
}
