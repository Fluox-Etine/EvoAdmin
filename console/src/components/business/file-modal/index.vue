<template>
  <a-modal v-model:open="open" width="1240px" title="文件资源库">
    <div style="width: 600px;float: right">
      <a-row>
        <a-col :span="14">
          <a-input-search placeholder="请输入文件名" style="width: 100%;"/>
        </a-col>
        <a-col :span="10">
          <a-flex justify="flex-end">
            <a-upload :multiple="true" :customRequest="handleUpload"
                      :show-upload-list="false">
              <a-button type="primary"> 本地上传</a-button>
            </a-upload>
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
    </div>

  </a-modal>
</template>

<script setup lang="ts">
import {ref} from "vue";
import {message, type UploadProps} from "ant-design-vue";

const open = ref<boolean>(true);

const openFileModal = () => {
  open.value = true
}

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

const activeKey = ref(0);


/** 上传文件之前钩子 */
const beforeUpload: UploadProps['beforeUpload'] = async (file) => {
  if (file.size / 1024 / 1024 > 2) {
    message.error('单个文件不超过2MB');
  } else {
    const item: FileItem = {
      file,
      uid: file.uid,
      name: file.name,
      size: file.size,
      status: '',
      percent: 0,
      thumbUrl: await fileToBase64(file),
    };
    fileList.value.push(item);
  }
  return false;
};

const handleUpload = (options, file, onSuccess, onError, OnProgress) => {
  console.log(options)
  console.log(file)
  console.log(onSuccess)
  console.log(onError)
  console.log(OnProgress)
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