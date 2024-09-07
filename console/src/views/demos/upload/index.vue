<template>
  <div>
    <SelectImage v-model="images" :multiple="true" :max-num="5"/>
    <SelectVideo v-model="video" :multiple="true" :default-list="defaultList" :max-num="15"/>
    <SelectFile v-model="file" :multiple="true"  :max-num="15"/>

  </div>

</template>

<script setup lang="ts">
import {ref, watch} from "vue";

const images = ref([])
const video = ref([])
const file = ref([])
const defaultList = ref([])

// 使用 watch 函数来监听 images 数组的变化
watch(images, (newValue) => {
  console.log('Images array has changed:', newValue);
  // 在这里执行你需要的操作，比如调用 API 或更新其他状态
});

watch(video, (newValue) => {
  console.log('video array has changed:', newValue);
  // 在这里执行你需要的操作，比如调用 API 或更新其他状态
});
</script>
<style scoped lang="less">

</style>