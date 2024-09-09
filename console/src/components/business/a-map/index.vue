<script setup>
import {defineEmits, defineProps, onMounted, onUnmounted, reactive} from "vue";
import AMapLoader from "@amap/amap-jsapi-loader";

const props = defineProps({
  // 绑定父组件的默认值
  modelValue: {
    type: Array,
    default: () => []
  },
  // 元素的尺寸(宽)
  width: {
    type: String,
    default: '100%'
  },
  // 元素的尺寸(高)
  height: {
    type: String,
    default: '84vh'
  },
});

let map = null
let marker = null
let geocoder = null
const key = import.meta.env.VITE_AMAP_KEY;
const secret = import.meta.env.VITE_AMAP_SECRET_KEY;
const emit = defineEmits(["update:modelValue"]);

const state = reactive({
  latLng: '',
  address: ''
})
const base64Icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAAZCAYAAADaILXQAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArlJREFUeNqllt1LU3EYx49Nj5EmLdfWZtHM2XHM7eyssjIhh60ta8WMdmEhgrdR3nTRVQODqFisS/sDgiIoiYgwyKAiki6ySE2DmmZMZ9uqIfTCr+/vdAZy9lt78eJzsbfP8+x5nj3PuFAoxKl5bnKVAx2wPTVJXSNGZ/eDjY6u24ZmYchgr2V9hgVLLIBLYOLVZomMmZ3kDRgzS2QUj/H8C3AWbCpYrmR7AsxObZNIrL2DpIJBkurpIQmfj3xtaSGLdjv5bHWQiQYnDfIS7CtUfhr8/NK+n/wYGCDf+/tJwu2WhSw+CiINEAPu/8rxBh9ILnT6SToSIUm/n8RzSBkBpoGFKccLevAoKraQdDhMEh5PXulyphvlABHAs+Q069+p3l6SDASKElPizXYqXwImlvzilEUk3/r6ihZneFsvN7ibJb8XFXeShNdbslwpzVWWfGhmeytZFMWS5fSbw3OFKf8klC7OJ78fFRwrkmcmhiUfeG9ZWeZ0PcATZMk9r7dIJYsX/o1iItcomsBItMmxkpIMgspcP/8ASNMsihHPYok9M7nm7hrsDo7jKoAGrGItrmvF1J7uHoh/XdcJ5yBsAg2gDqwDPChbLjeCUfo1C5FPYu3e1NseGjT8EYh8wAs6wG5QD1arD4UVzM/kqf8HJIDLNCny1Sch8avoBHvB+qw1ibMWRIA/MVuOOiPwY6MzfrxKf4YhphwEO0BN9g7muPI7hubw+FZnlngeARF86bzWfJkhPQwOgF1gg9xchlzjqlxrxiEenlI1mAYc1Ak3VEJaBg9oAxZQTR3MAy13Gc04VVO3Z9gojmcaTO/mLb1tpFZTcQyvH1Ia6AYuZUqqlHEsy3n9lwXgL2jr254Ypbl3yBgNHDdq+KPKRLSCRqCVp0LJNO9fC3X9qQANJsqIWZV6rlFnyeIvmIDJtcarlTEAAAAASUVORK5CYII='
onMounted(() => {
  window._AMapSecurityConfig = {
    securityJsCode: secret,
  };
  let center = [116.397428, 39.90923]
  if (props.modelValue.length > 1) {
    // 转换成数组 "116.4346,39.942381"
    const latLng = props.modelValue.split(',')
    center = [parseFloat(latLng[0]), parseFloat(latLng[1])]
  }
  AMapLoader.load({
    key: key, // 申请好的Web端开发者Key，首次调用 load 时必填
    version: "2.0", // 指定要加载的 JSAPI 的版本，缺省时默认为 1.4.15
    plugins: ["AMap.Marker"], //需要使用的的插件列表，如比例尺'AMap.Scale'，支持添加多个如：['...','...']
  })
      .then((AMap) => {
        map = new AMap.Map("container", {
          // 设置地图容器id
          viewMode: "3D", // 是否为3D地图模式
          zoom: 11, // 初始化地图级别
          center: center, // 初始化地图中心点位置
        });
        marker = new AMap.Marker();

        AMap.plugin("AMap.Geocoder", function () {
          geocoder = new AMap.Geocoder({});
        });

        map.on("click", function (ev) {
          handleClearMarker()
          handleAddMarker(ev.lnglat)
        });
      })
      .catch((e) => {
        console.log(e);
      });
});

/** 添加marker */
const handleAddMarker = (e) => {
  const lngLat = [e.lng, e.lat]
  state.latLng = e.lng + ',' + e.lat
  handleGeoCoder(lngLat)
  map.setCenter(lngLat)
  map.setZoom(15)
  if (!marker) {
    marker = new AMap.Marker({
      icon: base64Icon,
      position: [e.lng, e.lat],
      offset: new AMap.Pixel(-11, -25)
    })
    marker.setMap(map)
  }
}

/** 清除marker */
const handleClearMarker = () => {
  if (marker) {
    marker.setMap(null);
    marker = null;
  }
}

/** 逆地理编码 */
const handleGeoCoder = (lngLat) => {
  geocoder.getAddress(lngLat, function (status, result) {
    if (status === "complete" && result.info === "OK") {
      state.address = result.regeocode.formattedAddress
      emit("update:modelValue", [state.latLng, state.address])
    }
  });
}
onUnmounted(() => {
  if (map) {
    map?.destroy();
  }
});
</script>

<template>
  <div id="container" :style="{ width: `${width}`, height: `${height}` }">
    <a-row>
      <a-col :span="7" :offset="17">
        <a-card style="z-index: 999999;margin-top: 8px;" id="box">
          <div style="font-size: 16px; min-height:48px;display:block">
            <span v-if="state.address">{{ state.address }}</span>
            <span v-else>鼠标点击地图获取坐标</span>
          </div>
          <div style="margin-top:3px">
            <span style="font-size:14px; height: 22px;display:block;margin-bottom: 4px;">坐标</span>
            <a-input v-model:value="state.latLng"
                     style="padding: 0 0 0 8px; height: 32px;line-height: 32px;background:rgba(27, 32, 44, .03);border: 1px solid #ced2d9;border-radius: 4px;font-size: 14px;font-weight: 400;"/>
            <br>
            <br>
            <span style="font-size:14px; height: 22px;display:block;margin-bottom: 4px;">地址</span>
            <a-input v-model:value="state.address"
                     style="padding: 0 0 0 8px; height: 32px;line-height: 32px;background:rgba(27, 32, 44, .03);border: 1px solid #ced2d9;border-radius: 4px;font-size: 14px;font-weight: 400;"/>
          </div>
        </a-card>
      </a-col>
    </a-row>
  </div>
  <div id="box">

  </div>
</template>

<style scoped>
#container {
}

#box {
  background: #FFF;
  //padding: 30px 30px 0;
  color: #1b202c;
  font-weight: 600;
  border-radius: 10px;
}

.amap-icon img,
.amap-marker-content img {
  width: 41px;
  height: 55px;
}
</style>
