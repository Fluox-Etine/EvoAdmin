<script setup>
import {defineProps, onMounted, onUnmounted, ref} from "vue";
import AMapLoader from "@amap/amap-jsapi-loader";

const props = defineProps({
  // 经度
  lng: {
    type: Number,
    default: 116.397428
  },
  // 纬度
  lat: {
    type: Number,
    default: 39.90923
  },
  // 绑定父组件的默认值
  modelValue: {
    type: Array,
  },
  // 元素的尺寸(宽)
  width: {
    type: String,
    default: '100%'
  },
  // 元素的尺寸(高)
  height: {
    type: String,
    default: '800px'
  },
});

let map = null;
let marker = null
const key = import.meta.env.VITE_AMAP_KEY;
const secret = import.meta.env.VITE_AMAP_SECRET_KEY;
const base64Icon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAAZCAYAAADaILXQAAAACXBIWXMAAC4jAAAuIwF4pT92AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAArlJREFUeNqllt1LU3EYx49Nj5EmLdfWZtHM2XHM7eyssjIhh60ta8WMdmEhgrdR3nTRVQODqFisS/sDgiIoiYgwyKAiki6ySE2DmmZMZ9uqIfTCr+/vdAZy9lt78eJzsbfP8+x5nj3PuFAoxKl5bnKVAx2wPTVJXSNGZ/eDjY6u24ZmYchgr2V9hgVLLIBLYOLVZomMmZ3kDRgzS2QUj/H8C3AWbCpYrmR7AsxObZNIrL2DpIJBkurpIQmfj3xtaSGLdjv5bHWQiQYnDfIS7CtUfhr8/NK+n/wYGCDf+/tJwu2WhSw+CiINEAPu/8rxBh9ILnT6SToSIUm/n8RzSBkBpoGFKccLevAoKraQdDhMEh5PXulyphvlABHAs+Q069+p3l6SDASKElPizXYqXwImlvzilEUk3/r6ihZneFsvN7ibJb8XFXeShNdbslwpzVWWfGhmeytZFMWS5fSbw3OFKf8klC7OJ78fFRwrkmcmhiUfeG9ZWeZ0PcATZMk9r7dIJYsX/o1iItcomsBItMmxkpIMgspcP/8ASNMsihHPYok9M7nm7hrsDo7jKoAGrGItrmvF1J7uHoh/XdcJ5yBsAg2gDqwDPChbLjeCUfo1C5FPYu3e1NseGjT8EYh8wAs6wG5QD1arD4UVzM/kqf8HJIDLNCny1Sch8avoBHvB+qw1ibMWRIA/MVuOOiPwY6MzfrxKf4YhphwEO0BN9g7muPI7hubw+FZnlngeARF86bzWfJkhPQwOgF1gg9xchlzjqlxrxiEenlI1mAYc1Ak3VEJaBg9oAxZQTR3MAy13Gc04VVO3Z9gojmcaTO/mLb1tpFZTcQyvH1Ia6AYuZUqqlHEsy3n9lwXgL2jr254Ypbl3yBgNHDdq+KPKRLSCRqCVp0LJNO9fC3X9qQANJsqIWZV6rlFnyeIvmIDJtcarlTEAAAAASUVORK5CYII='
onMounted(() => {
  window._AMapSecurityConfig = {
    securityJsCode: secret,
  };
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
          center: [116.397428, 39.90923], // 初始化地图中心点位置
        });
        marker = new AMap.Marker();

        map.on("click", function (ev) {
          handleClearMarker()
          handleAddMarker(ev.lnglat)
        });
      })
      .catch((e) => {
        console.log(e);
      });
});

const handleAddMarker = (e) => {
  const lngLat = [e.lng, e.lat]
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
  // parent.setCoordinate([lng, lat, address])
}

const handleClearMarker = () => {
  if (marker) {
    marker.setMap(null);
    marker = null;
  }
}
onUnmounted(() => {
  map?.destroy();
});
</script>

<template>
  <div id="container" :style="{ width: `${width}`, height: `${height}` }">
    <a-row>
      <a-col :span="8" :offset="16">
        <a-card style="z-index: 999999;margin-top: 8px;">
          <a-space>
            坐标: 114.123, 39.123
          </a-space>
          <a-space>
            地址: 阿多诺金卡使得卡斯阿斯顿把三剑客的巴克斯郡
          </a-space>
        </a-card>
      </a-col>
    </a-row>
  </div>
</template>

<style scoped>
</style>
