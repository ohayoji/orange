
//jQuery
declare var $: any;

import { AreaStatus } from './area-status';

export class Area extends AreaStatus {
  id: number;
  name: string;
  selectedForOccupancy: boolean;

  constructor(_area: any, _times: any){
    super();

    this.id = _area.id;
    this.name = _area._name;
    this.seats = _area.seats;
    this.businesstimes = 0;
    this.notBusinesstimes = 0;
    this.setSeatsTotal(_times);
    this.setOccupancyRate();
    this.selectedForOccupancy = true;
  }

  setSeatsTotal(times: any): void{

    for (let time of times) {
        if (time.biz_type == 'inbiz') {
          this.businesstimes++;
        }else {
          this.notBusinesstimes++;
        }
    }
    this.businesstimeSeatsTotal = this.seats * this.businesstimes;
    this.notBusinesstimeSeatsTotal = this.seats * this.notBusinesstimes;
  }

  setOccupancyRate(): void{
    //本来_receiptsオブジェクトから算出すべきだが、妥協してビューの状態から算出する
    const sleepSeatsInBusiness = $(`.area_reserv_table[data-area_id=${this.id}] td.inbiz[title=off]`).length;
    const sleepSeatsNotBusiness = $(`.area_reserv_table[data-area_id=${this.id}] td.outbiz[title=off]`).length;
    this.activeSeatsInBusiness = this.businesstimeSeatsTotal - sleepSeatsInBusiness;
    this.activeSeatsOutBusiness = this.notBusinesstimeSeatsTotal - sleepSeatsNotBusiness;
    this.businesstimeOccupancyRate = this.activeSeatsInBusiness / this.businesstimeSeatsTotal;
    this.notBusinesstimeOccupancyRate = this.activeSeatsOutBusiness / this.notBusinesstimeSeatsTotal;

  }
}
