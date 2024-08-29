<template>
  <a-modal v-model:open="open" width="960px" title="文件资源库">
    <div style="width: 600px;float: right">
      <a-row>
        <a-col :span="14">
          <a-input-search placeholder="请输入文件名" style="width: 100%;"/>
        </a-col>
        <a-col :span="10">
          <a-flex justify="flex-end">
            <a-button type="primary" @click="handleUpload"> 本地上传</a-button>
            <a-button type="dashed" style="margin: 0 10px 0 10px">添加分组</a-button>
          </a-flex>
        </a-col>
      </a-row>
    </div>
    <div style="height: 650px;">
      <br>
      <a-tabs
          v-model:activeKey="activeKey"
          tab-position="left"
          :style="{ height: '650px'}"
      >
        <a-tab-pane v-for="(item,index) in groupList" :key="index" :tab="`${item.name}`">
          <div v-show="fileType == FileTypeEnum.IMAGE">
            我是图片
          </div>
          <div v-show="fileType == FileTypeEnum.VIDEO">
            我是视频
          </div>
          <div v-show="fileType == FileTypeEnum.FILE">
            我是文件
          </div>
        </a-tab-pane>
      </a-tabs>
      <UploadModal ref="UploadModalRef" @upload-success="handleUploadSuccess"/>
    </div>
  </a-modal>
</template>

<script setup lang="ts">
import {ref} from "vue";
import * as GroupApi from '@/api/backend/file_group.ts'
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";

const open = ref<boolean>(false);
const UploadModalRef = ref();
const activeKey = ref(0);
const fileType = ref<FileTypeEnum>(10);
const groupList = ref([
  {
    id: 0,
    name: '默认全部',
  }
])

const openFileModal = (type: FileTypeEnum) => {
  fetchGroupList()
  open.value = true
  fileType.value = type
}

/** 打开上传文件弹窗 */
const handleUpload = () => {
  UploadModalRef.value.openUploadModal(fileType, activeKey)
}

/** 获取分组列表 */
const fetchGroupList = async () => {
  const response = await GroupApi.list()
  groupList.value = [...groupList.value, ...response]
}

/** 上传成功回调 */
const handleUploadSuccess = () => {
  console.log('上传成功')
}
// 暴露方法
defineExpose({
  openFileModal
})
</script>
<style scoped lang="less">
.fixed-width-tab {
  width: 200px; /* 设置固定宽度 */
  /* 其他需要的样式 */
}
</style>