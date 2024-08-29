<template>
  <a-modal v-model:open="open" width="1240px" title="文件资源库">
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
    <div style="height: 700px;">
      <br>
      <a-tabs
          v-model:activeKey="activeKey"
          tab-position="left"
          :style="{ height: '700px'}"
      >
        <a-tab-pane v-for="(item,index) in groupList" :key="index" :tab="`${item.name}`">
          {{ item.name }}
        </a-tab-pane>
      </a-tabs>
      <!--      <a-modal v-model:open="visible" width="1240px" title="上传文件">-->
      <!--      </a-modal>-->
      <UploadModal ref="UploadModalRef"/>
    </div>
  </a-modal>
</template>

<script setup lang="ts">
import {ref} from "vue";

const open = ref<boolean>(false);

const groupList = ref([
  {
    id: 0,
    name: '默认全部',
  },
  {
    id: 1,
    name: '社区资源',
  }
])

const UploadModalRef = ref();
const activeKey = ref(0);


const openFileModal = () => {
  open.value = true
}

/** 打开上传文件弹窗 */
const handleUpload = () => {
  UploadModalRef.value.openUploadModal()
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