<template>
  <a-modal v-model:open="open" :width="985" title="文件资源库" @ok="onOk">
    <div style="width: 85%;float: right">
      <a-flex justify="space-between" align="center">
        <a-input-search placeholder="请输入文件名" style="width: 40%;"/>
        <a-space :size="15">
          <a-button danger type="dashed" @click="handleChunkUpload"> 大文件上传</a-button>
          <a-button type="primary" @click="handleUpload"> 普通上传</a-button>
          <a-button type="dashed">添加分组</a-button>
        </a-space>
      </a-flex>
    </div>
    <div style="height: 650px;">
      <br>
      <a-tabs
          v-model:activeKey="activeKey"
          tab-position="left"
          :style="{ height: '650px', width: '100%'}"
          @change="fetchFileList"
      >
        <a-tab-pane v-for="group in groupList" :key="group.value" :tab="`${group.label}`">
          <a-card class="w-full mt-4 h-600px">
            <div class="file-list-body">
              <!-- 文件列表 -->
              <ul v-if="fileList && fileList.length" class="file-list-ul clearfix">
                <li
                    class="file-item"
                    :class="{ active: selectedItems.indexOf(item) > -1 }"
                    v-for="(item, index) in fileList"
                    :key="index"
                    @click="onSelectItem(item)"
                >
                  <div
                      v-if="item.file_type === FileTypeEnum.IMAGE"
                      class="img-cover"
                      :style="{ backgroundImage: `url('${domain+item.file_path}')`, width: '95px' }"
                  ></div>
                  <div
                      v-if="item.file_type !== FileTypeEnum.IMAGE"
                      class="img-cover"
                      :style="handleBackgroundStyle(item)"
                  ></div>
                  <div class="file-name oneline-hide">{{ item.file_name }}</div>
                  <div class="select-mask">
                    <CheckOutlined class="selected-icon" type="check"/>
                  </div>
                </li>
              </ul>
              <!-- 无数据时显示 -->
              <a-empty v-else-if="!isLoading"/>
              <!-- 底部操作栏 -->
              <div class="footer-operate clearfix">
              </div>
            </div>
          </a-card>
        </a-tab-pane>
      </a-tabs>
      <UploadModal ref="UploadModalRef" @upload-success="handleUploadSuccess"/>
      <ChunkModal ref="ChunkModalRef" @upload-success="handleUploadSuccess"/>
    </div>
  </a-modal>
</template>

<script setup lang="ts">
import {ref} from "vue";
import * as GroupApi from '@/api/backend/uploadGroup.ts'
import * as FileApi from '@/api/backend/file.ts'
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";
import {CheckOutlined} from "@ant-design/icons-vue";
import {message as $message} from "ant-design-vue/es/components";

const emit = defineEmits(['handleSubmit']);
const domain = import.meta.env.VITE_DOMAIN_URL;
const open = ref<boolean>(false);
const UploadModalRef = ref();
const ChunkModalRef = ref();
const activeKey = ref(0);
const fileType = ref<FileTypeEnum>(10);
const multiple = ref<number>(false)
const maxNum = ref<number>(1)
const selectedNum = ref<number>(0)
const isLoading = ref(false)
const selectedItems = ref<any>([])
const pagination = ref({
  currentPage: 1,
  itemCount: 0
})
const fileList = ref([])
const groupList = ref([])

/** 打开文件资源库弹窗 */
const openFileModal = (type: FileTypeEnum, isMultiple: boolean, max: number, selected: number) => {
  selectedItems.value = []
  fileType.value = type
  multiple.value = isMultiple
  maxNum.value = max
  selectedNum.value = selected
  fetchFileList()
  fetchGroupList()
  open.value = true
}

/** 打开上传文件弹窗 */
const handleUpload = () => {
  UploadModalRef.value.openUploadModal(fileType, activeKey)
}

/** 打开大文件上传弹窗 */
const handleChunkUpload = () => {
  ChunkModalRef.value.openChunkUploadModal(fileType, activeKey)
}

/** 获取分组列表 */
const fetchGroupList = async () => {
  groupList.value = await GroupApi.selectGroupList()
}

/** 获取当前类型的文件列表 */
const fetchFileList = async () => {
  isLoading.value = true
  const {meta, items} = await FileApi.list({page: 1, file_type: fileType.value, group_id: activeKey.value})
  pagination.value = meta
  fileList.value = items
  isLoading.value = false
}

/** 上传成功回调 */
const handleUploadSuccess = () => {
  pagination.value.currentPage = 1
  fetchFileList()
}

/** 点击文件列表项 */
const onSelectItem = function (item) {
  // 记录选中状态
  if (!multiple.value) {
    selectedItems.value = [item]
    return
  }
  const key = selectedItems.value.indexOf(item)
  const selected = key > -1
  // 验证数量限制
  if (!selected && (selectedItems.value.length + selectedNum.value) >= maxNum.value) {
    $message.warning(`最多可选${maxNum.value}个文件`, 1)
    return
  }
  if (!selected) {
    selectedItems.value.push(item)
  } else {
    selectedItems.value.splice(key, 1)
  }
}

/** 获取背景样式 */
const handleBackgroundStyle = (item) => {
  let path = ''
  if (item.file_type === FileTypeEnum.VIDEO) {
    path = domain + '/file_icons/video/' + item.file_ext + '.png'
  } else if (item.file_type === FileTypeEnum.FILE) {
    path = domain + '/file_icons/file/' + item.file_ext + '.png'
  }
  return {
    backgroundImage: `url('${path}')`,
    width: '95px',
  };
}
const onOk = () => {
  emit('handleSubmit', selectedItems.value)
  open.value = false
}

// 暴露方法
defineExpose({
  openFileModal
})
</script>
<style scoped lang="less">
.fixed-width-tab {
  width: 200px;
}

.file-list-body {
  height: 455px;

  .file-list-ul {
    margin: 0;
    padding: 0;
    height: 417px;
    list-style-type: none;
  }

  .file-item {
    width: 110px;
    position: relative;
    cursor: pointer;
    border-radius: 2px;
    padding: 4px;
    border: 1px solid rgba(0, 0, 0, 0.05);
    float: left;
    margin: 8px;
    -webkit-transition: All 0.2s ease-in-out;
    -moz-transition: All 0.2s ease-in-out;
    -o-transition: All 0.2s ease-in-out;
    transition: All 0.2s ease-in-out;

    &:hover {
      border: 1px solid #16bce2;
    }
  }

  .file-item {
    // 文件名称
    .file-name {
      font-size: 12px;
      text-align: center;
      color: #3b4acc;
      margin-top: 5px;
    }

    // 预览图
    .img-cover {
      margin: 0 auto;
      width: 95px;
      height: 95px;
      background: no-repeat center center / 100%;
    }

    // 遮罩层(选中时)
    &.active .select-mask {
      display: block;
    }

    .select-mask {
      display: none;
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(0, 0, 0, 0.45);
      text-align: center;
      border-radius: 2px;

      .selected-icon {
        font-size: 26px;
        color: #fff;
        text-align: center;
        position: absolute;
        top: 38%;
        left: 38%;
      }
    }
  }

  // 底部操作栏
  .footer-operate {
    height: 28px;
    margin-top: 10px;

    .footer-desc {
      color: #999;
      margin-right: 10px;
    }

    .btn-mini {
      font-size: 13px;
      padding: 0 15px;
      height: 28px;
    }
  }
}
</style>