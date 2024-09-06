<template>
  <div class="image-list clearfix" :class="{ multiple }">
    <VueDraggable
        ref="el"
        v-model="selectedItems"
        :animation="150"
        @end="onEnd"
    >
      <div
          v-for="(item, index) in selectedItems"
          :key="index"
          class="file-item"
          :style="{ width: `${width}px`, height: `${width}px` }"
      >
        <!-- 预览图 -->
        <a :href="domain+item" target="_blank">
          <div class="img-cover" :style="{ backgroundImage: `url('${domain+item}')`}"></div>
        </a>
        <CloseCircleTwoTone class="icon-close" @click="handleDeleteFileItem(index)"/>
      </div>
    </VueDraggable>
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
import {
  type UseDraggableReturn,
  VueDraggable
} from 'vue-draggable-plus'
import {ref, defineProps, defineEmits} from "vue";
import {PlusOutlined} from '@ant-design/icons-vue';
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";
import {CloseCircleTwoTone} from "@ant-design/icons-vue";

const props = defineProps({
  // 默认显示的图片
  defaultList: {
    type: Array,
    default: () => []
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
    default: false
  },
  // 最大选择的数量限制, multiple模式下有效
  maxNum: {
    type: Number,
    default: 10
  },
  modelValue: {
    type: Array
  }
});

const emit = defineEmits(["update:modelValue"]);
const domain = import.meta.env.VITE_DOMAIN_URL;
const selectedItems = ref([]);
const FilesModal = ref<any>();
const el = ref<UseDraggableReturn>()
/** 打开文件选择器 */
const handleSelectImage = () => {
  const length = props.defaultList?.length || 0;
  FilesModal.value.openFileModal(FileTypeEnum.IMAGE, props.multiple, props.maxNum, length);
}

/** 文件选择器提交回调 */
const handleSelectImageSubmit = (result) => {
  if (result.length > 0) {
    const fileList = result.map(item => {
      return item.file_path;
    })
    selectedItems.value = props.multiple
        ? [...selectedItems.value, ...fileList]
        : fileList;
  }
}

/** 删除文件 */
const handleDeleteFileItem = (index) => {
  selectedItems.value.splice(index, 1)
}

/** 拖动结束回调 */
const onEnd = () => {
  emit('update:modelValue', selectedItems.value)
}

</script>
<style lang="less" scoped>
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