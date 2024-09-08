<template>
  <div :style="{ width: `${width}px`, height: `${height}px` }" :id="id">
    <img
        v-if="showImage"
        style="background-color: black; object-fit: cover"
        :width="props.width"
        :height="props.height"
        :src="props.poster"
        @click="clickImage"
        alt="背景图片"/>
  </div>
</template>

<script setup lang="ts">
import {onUnmounted, ref} from "vue";
import Player from "xgplayer";
import "xgplayer/dist/index.min.css";

defineOptions({
  name: 'XgPlayer',
});
const props = defineProps({
  id: {
    type: String,
    required: true
  },
  videoUrl: {
    type: String,
    default: () => ""
  },
  poster: {
    type: String,
    default: () => ""
  },
  playsinline: {
    type: Boolean,
    default: false
  },
  width: {
    type: String,
    default: "100%"
  },
  height: {
    type: String,
    default: "100%"
  }
});
const showImage = ref(true);
// 定义一个变量来存储 player 实例
let player: Player;

const clickImage = () => {
  if (!player) {
    initPlayer();
    showImage.value = false;
  }
};

onUnmounted(() => {
  // 在这里执行清理工作
  if (player) {
    player.destroy();
  }
});

// 销毁播放器实例
const destroyPlayer = () => {
  console.log("销毁播放器实例")
  if (player) {
    player.destroy();
  }
};

// 初始化西瓜视频
const initPlayer = () => {
  player = new Player({
    lang: "zh",
    volume: 0.5,
    id: props.id,
    url: props.videoUrl,
    // poster: props.poster,
    playsinline: props.playsinline,
    height: props.height,
    width: props.width,
    commonStyle: {
      // 进度条底色
      progressColor: "",
      // 播放完成部分进度条底色
      playedColor: "#ff9700",
      // 缓存部分进度条底色
      cachedColor: "",
      // 进度条滑块样式
      sliderBtnStyle: {},
      // 音量颜色
      volumeColor: "#ff9700",
    },
    playbackRate: [0.25, 0.5, 1, 1.5, 2, 3],
    autoplay: true,
    whitelist: [""]
  });
  player.play();
};

defineExpose({destroyPlayer});

</script>
