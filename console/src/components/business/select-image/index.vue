<template>
  <div class="image-list clearfix" :class="{ multiple }">
    <div class="draggable-item" type="transition" :name="'flip-list'">
      <div
          v-for="(item, index) in selectedItems"
          :key="item.id"
          class="file-item"
          :style="{ width: `${width}px`, height: `${width}px` }"
      >
        <!-- 预览图 -->
        <a :href="domain+item.file_path" target="_blank">
        <div class="img-cover" :style="{ backgroundImage: `url('${domain+item.file_path}')`}"></div>
        </a>
        <CloseCircleTwoTone   class="icon-close" @click="handleDeleteFileItem(index)"/>
      </div>
    </div>
    <div
        v-show="(!multiple && selectedItems.length <= 0) || (multiple && selectedItems.length < maxNum)"
        class="selector"
        :style="{ width: `${width}px`, height: `${width}px` }"
        title="点击选择图片"
        @click="handleSelectImage">
      <PlusOutlined class="icon-plus" :style="{ fontSize: `${width * 0.4}px` }"/>
    </div>
  </div>
  <FileModal ref="FilesModal" :multiple="false" @handleSubmit="handleSelectImageSubmit"/>
</template>
<script setup lang="ts">

import {ref} from "vue";
import {PlusOutlined} from '@ant-design/icons-vue';
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";
import {CloseCircleTwoTone} from "@ant-design/icons-vue";

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps({
  // 默认显示的图片
  defaultList: {
    type: Array
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
  },
  // 是否多选
  multiple: {
    type: Boolean,
    default: true
  },
  // 最大选择的数量限制, multiple模式下有效
  maxNum: {
    type: Number,
    default: 10
  }
});
const domain = import.meta.env.VITE_DOMAIN_URL;
const selectedItems = ref([]);
const FilesModal = ref<any>();

/** 打开文件选择器 */
const handleSelectImage = () => {
  FilesModal.value.openFileModal(FileTypeEnum.IMAGE, props.multiple, props.maxNum, props.defaultList?.length);
}

/** 文件选择器提交回调 */
const handleSelectImageSubmit = (result) => {
  if (result.length > 0) {
    const fileList = result.map(item => {
      return {id: item.id, file_path: item.file_path};
    })
    console.log(props.multiple)
    selectedItems.value = props.multiple
        ? [...selectedItems.value, ...fileList]
        : fileList;
  }
}

/** 删除文件 */
const handleDeleteFileItem = (index) => {
  selectedItems.value.splice(index, 1)
}

const onUpdate = (e) => {

}

</script>


<style lang="less" scoped>
/deep/ .flip-list-move {
  transition: transform 0.3s !important;
}

/deep/ .no-move {
  transition: transform 0s;
}

.image-list {
  // 多选模式下margin
  &.multiple {
    .file-item,
    .selector {
      margin-right: 10px;
      margin-bottom: 10px;
    }
  }
}

// 文件元素
.file-item {
  position: relative;
  float: left;
  width: 80px;
  height: 80px;
  position: relative;
  padding: 2px;
  border: 1px solid #ddd;
  background: #fff;

  .img-cover {
    display: block;
    width: 100%;
    height: 100%;
    background: no-repeat center center / 100%;
  }

  &:hover {
    .icon-close {
      display: block;
    }
  }

  .icon-close {
    display: none;
    position: absolute;
    top: -8px;
    right: -8px;
    cursor: pointer;
    font-size: 16px;
    color: #c5c5c5;

    &:hover {
      color: #7d7d7d;
    }
  }

  &:hover {
    border: 1px solid #a7c3de;
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