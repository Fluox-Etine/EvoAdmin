<template>
  <div>
    <SelectImage v-model="images" :multiple="true" :max-num="5"/>
  </div>
</template>

<script setup lang="ts">
import {ref, watch} from "vue";

const images = ref([])

// 使用 watch 函数来监听 images 数组的变化
watch(images, (newValue, oldValue) => {
  console.log('Old value:', oldValue);
  console.log('Images array has changed:', newValue);
  // 在这里执行你需要的操作，比如调用 API 或更新其他状态
});
</script>
<style scoped lang="less">

</style>