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
          :style="{ height: '650px', width: '100%'}"
      >
        <a-tab-pane v-for="(item,index) in groupList" :key="index" :tab="`${item.name}`">
        </a-tab-pane>
      </a-tabs>
      <UploadModal ref="UploadModalRef" @upload-success="handleUploadSuccess"/>
    </div>
  </a-modal>
</template>

<script setup lang="ts">
import {ref} from "vue";
import * as GroupApi from '@/api/backend/file_group.ts'
import * as FileApi from '@/api/backend/file.ts'
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";

const domain = import.meta.env.VITE_DOMAIN_URL;
const open = ref<boolean>(false);
const UploadModalRef = ref();
const activeKey = ref(0);
const fileType = ref<FileTypeEnum>(10);
const pagination = ref({
  currentPage: 1,
  itemCount: 0
})
const fileList = ref([])

const groupList = ref([{id: 0, name: '全部分组'}])

const openFileModal = (type: FileTypeEnum) => {
  fileType.value = type
  fetchFileList()
  fetchGroupList()
  open.value = true

}

/** 打开上传文件弹窗 */
const handleUpload = () => {
  UploadModalRef.value.openUploadModal(fileType, activeKey)
}

/** 获取分组列表 */
const fetchGroupList = async () => {
  const response = await GroupApi.list()
  response.forEach(item => {
    if (!groupList.value.some(existingItem => existingItem.id === item.id)) {
      groupList.value.push(item);
    }
  });
}

/** 获取当前类型的文件列表 */
const fetchFileList = async () => {
  const {meta, items} = await FileApi.list({page: 1, file_type: fileType.value, group_id: activeKey.value})
  pagination.value = meta
  fileList.value = items
}
/** 上传成功回调 */
const handleUploadSuccess = () => {
  pagination.value.currentPage = 1
  fetchFileList()
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