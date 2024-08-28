<template>
  <div class="image-custom">
    <a-tooltip>
      <template v-if="tips" v-slot:title>{{ tips }}</template>
      <div v-if="imgUrl" class="image-box" :style="{ width: `${width}px`, height: `${height}px` }">
        <img :src="imgUrl" alt/>
        <div class="update-box-black"></div>
        <div class="uodate-repalce" @click="handleSelectImage">替换</div>
      </div>
      <div
          v-else
          class="selector"
          :style="{ width: `${width}px`, height: `${width}px` }"
          @click="handleSelectImage"
      >
        <PlusOutlined class="icon-plus" :style="{ fontSize: `${width * 0.4}px` }"/>
      </div>
    </a-tooltip>
  </div>
  <FileModal ref="FilesModal" :multiple="false" @handleSubmit="handleSelectImageSubmit"/>
</template>
<script setup lang="ts">

import {ref} from "vue";
import {PlusOutlined} from '@ant-design/icons-vue';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
  // 默认显示的图片
  value: {
    type: String,
    default: ''
  },
  // 气泡提示内容
  tips: {
    type: String,
    default: ''
  },
  // 元素的尺寸(宽)
  width: {
    type: Number,
    default: 70
  },
  // 元素的尺寸(高)
  height: {
    type: Number,
    default: 70
  }
});

const imgUrl = ref('');
const FilesModal = ref<any>();

/** 打开文件选择器 */
const handleSelectImage = () => {
  console.log('打开文件选择器')
  FilesModal.value.openFileModal();
}

const handleSelectImageSubmit = (result) => {
  console.log(result)
}

</script>


<style lang="less" scoped>
.image-custom {
  display: flex;
  align-items: center;

  .image-box {
    position: relative;
    width: 70px;
    height: 70px;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 4px;

    .update-box-black {
      background: #000;
      opacity: 0.5;
      display: none;
      position: absolute;
      z-index: 2;
      top: 0;
      right: 0;
      left: 0;
      bottom: 0;
      border-radius: 4px;
    }

    .uodate-repalce {
      width: 60px;
      height: 30px;
      font-size: 12px;
      text-align: center;
      position: absolute;
      top: 0;
      right: 0;
      left: 0;
      bottom: 0;
      margin: auto;
      display: none;
      z-index: 2;
      background: #fff;
      border-radius: 4px;
      font-weight: 600;
      color: #595961;
      line-height: 30px;
    }

    &:hover {
      .update-box-black,
      .uodate-repalce {
        display: block;
      }
    }

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 4px;
    }
  }
}

// 选择器
.selector {
  width: 80px;
  height: 80px;
  float: left;
  border: 1px dashed #e2e2e2;
  text-align: center;
  color: #dad9d9;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;

  &:hover {
    border: 1px dashed #40a9ff;
    color: #40a9ff;
  }

  .icon-plus {
    font-size: 32px;
  }
}
</style>