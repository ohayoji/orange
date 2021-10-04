import Vue from 'vue';
import slashSeparateListItem from '../components/list/slash-separate-list-item.vue';

import { Area } from '../classes/area';
import { Floor } from '../classes/floor';

$(function(){
  let areas = [];

  const viewType = _condition.view_type;
  //稼働率エリア選択
  let areaSelectable = false;
  let displayAreas;
  if (viewType == 'nomal' || viewType == 'all') {
    //全エリア表示時
    displayAreas = _areas;
  }else{
    //単一エリア表示時
    displayAreas = _areas.filter(area => area._name == viewType);
  }
  //エリアが複数あれば稼働率エリア選択フラグオン
  if (displayAreas.length > 1) {
    areaSelectable = true;
  }

  for (let _area of displayAreas) {
      let area = new Area(_area, _times);
      // console.log(area.name, area);
      areas.push(area);
  }

  let floor = new Floor(areas);
  // console.log('floor', floor);

  if (_visiter != "staff") {

    $("div#occupancy_rate_display").show();

    // occupancy_rate_display
    let occupancyRateDisplay = new Vue({
      el: '#occupancy_rate_display',
      data: {
        floor: floor,
        areaSelectable: areaSelectable
      },
      components: {
        'slash-separate-list-item': slashSeparateListItem
      },
      methods: {
        select: function() {
          if (!this.areaSelectable){
            return false;
          }
          $("#right_slide").open({
            dispContentsId: 'area_select',
            title:"エリアを選択"
          });
        }
      }

    });

    //area_select
    let areaSelect = new Vue({
      el: '#area_select',
      data: {
        areas: areas,
      },
      methods: {
        select: function() {
          let selectedAreas = [];
          for (let area of this.areas) {
              if (area.selectedForOccupancy) selectedAreas.push(area);
          }
          floor = new Floor(selectedAreas);

          //occupancyRateDisplayのデータを更新
          occupancyRateDisplay.floor = floor;
        }
      }
    })
	}
})
