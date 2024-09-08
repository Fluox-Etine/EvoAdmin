<template>
  <div class="image-list clearfix" :class="{ multiple }">
    <VueDraggable
        ref="el"
        v-model="selectedItems"
        :animation="150"
        @end="onChange"
    >
      <div
          v-for="(item, index) in selectedItems"
          :key="index"
          class="file-item"
          :style="{ width: `${width}px`, height: `${width}px` }"
      >
        <!-- 预览图 -->
        <a-tooltip>
          <template #title>{{ handleGetFileName(item) }}</template>
          <a :href="domain+item" target="_blank">
            <div class="img-cover" :style="handleBackgroundStyle(item)"></div>
          </a>
        </a-tooltip>
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
  <FileModal ref="FilesModal" :multiple="false" @handleSubmit="handleSelectFileSubmit"/>
</template>

<script setup lang="ts">
import {type UseDraggableReturn, VueDraggable} from 'vue-draggable-plus'
import {defineEmits, defineProps, ref, watch} from "vue";
import {CloseCircleTwoTone, PlusOutlined} from '@ant-design/icons-vue';
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";
import _ from "lodash-es";

const props = defineProps({
  // 默认选中的值
  defaultList: {
    type: Array,
    default: () => []
  },
  // 绑定父组件的默认值
  modelValue: {
    type: Array,
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
  }
});

const emit = defineEmits(["update:modelValue"]);
const domain = import.meta.env.VITE_DOMAIN_URL;
const selectedItems = ref([]);
const FilesModal = ref<any>();
const el = ref<UseDraggableReturn>()
const allowProps = ref<boolean>(true);

// 使用 watch 来监视 defaultList 的变化. 默认值变化时，更新 selectedItems
watch(() => props.defaultList, (newVal) => {
  if (newVal.length && !selectedItems.value.length && allowProps) {
    selectedItems.value = newVal.map(item => {
      return item;
    });
  }
}, {
  immediate: true // 立即触发
});


/** 打开文件选择器 */
const handleSelectImage = () => {
  FilesModal.value.openFileModal(FileTypeEnum.FILE, props.multiple, props.maxNum, selectedItems.value.length);
}

/** 文件选择器提交回调 */
const handleSelectFileSubmit = (result) => {
  if (result.length) {
    const fileList = result.map(item => {
      return item.file_path;
    })
    selectedItems.value = props.multiple
        ? _.uniq(selectedItems.value.concat(fileList))
        : fileList;
    onChange();
  }
}

/** 删除文件 */
const handleDeleteFileItem = (index) => {
  selectedItems.value.splice(index, 1)
  if (selectedItems.value.length === 0) {
    allowProps.value = false
  }
  onChange();
}

/** 拖动结束回调 */
const onChange = () => {
  emit('update:modelValue', selectedItems.value)
}

/** 获取背景样式 */
const handleBackgroundStyle = (filePath) => {
  const lastIndex = filePath.lastIndexOf('.');
  let ext = 'doc'
  if (lastIndex > 0) {
    ext = filePath.substring(lastIndex + 1);
  }
  let path = domain + '/file_icons/file/' + ext + '.png'
  return {
    backgroundImage: `url('${path}')`,
  };
}

/** 获取文件名 */
const handleGetFileName = (filePath) => {
  const lastIndex = filePath.lastIndexOf('/');
  return filePath.substring(lastIndex + 1);
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