<template>
  <DraggableModal
      v-model:open="visible"
      title="上传"
      :width="800"
      ok-text="开始上传"
      :ok-button-props="{ disabled: disabledUpload }"
      @ok="onOk"
      @cancel="onCancel"
  >
<!--    <a-flex justify="space-between" align="center">-->
      <a-alert message="普通上传单个文件不超过5MB，最多只能上传10个文件" type="info" show-icon/>
      <a-upload :multiple="true" :before-upload="beforeUpload" :show-upload-list="false">
        <a-button type="primary"> 普通上传</a-button>
      </a-upload>
<!--    </a-flex>-->
    <DynamicTable :search="false" :data-source="fileList" :columns="columns"/>
  </DraggableModal>
</template>

<script setup lang="tsx">
import {ref, computed} from 'vue';
import {message, type UploadProps} from 'ant-design-vue';
import {UploadResultStatus, fileListColumns, type FileItem} from './columns.tsx';
import {DraggableModal} from '@/components/business/draggable-modal/index.ts';
import {useTable, type TableColumn} from '@/components/business/dynamic-table';
import * as Api from '@/api/backend/upload.ts';

const emit = defineEmits(['uploadSuccess']);

const [DynamicTable] = useTable();

const visible = ref(false);
const fileList = ref<FileItem[]>([]);

const disabledUpload = computed(() => {
  return !fileList.value.some((n) => n.status !== UploadResultStatus.SUCCESS);
});

const fileToBase64 = (file: File): Promise<string> => {
  const {promise, resolve, reject} = Promise.withResolvers<string>();

  const reader = new FileReader();

  reader.onload = () => {
    resolve(reader.result as string);
  };
  reader.onerror = (error) => {
    reject(error);
  };

  reader.readAsDataURL(file);
  return promise;
};

const onCancel = () => {
  const hasSuccess = fileList.value.some((n) => n.status === UploadResultStatus.SUCCESS);
  if (hasSuccess) {
    emit('uploadSuccess');
  }
  fileList.value = [];
};

const onOk = async () => {
  const uploadFileList = fileList.value.filter((n) => n.status !== UploadResultStatus.SUCCESS);
  await Promise.all(
      uploadFileList.map(async (item) => {
        try {
          await Api.uploadUpload({file: item.file}, undefined, {
            onUploadProgress(progressEvent) {
              const complete = ((progressEvent.loaded / progressEvent.total!) * 100) | 0;
              item.percent = complete;
              item.status = UploadResultStatus.UPLOADING;
            },
          });
          item.status = UploadResultStatus.SUCCESS;
        } catch (error) {
          console.log(error);
          item.status = UploadResultStatus.ERROR;
        }
      }),
  );
};

const beforeUpload: UploadProps['beforeUpload'] = async (file) => {
  if (file.size / 1024 / 1024 > 5) {
    message.error('单个文件不超过5MB');
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

const handleRemove = (record: FileItem) => {
  fileList.value = fileList.value.filter((n) => n.uid !== record.uid);
};

const columns: TableColumn<FileItem>[] = [
  ...fileListColumns,
  {
    width: 120,
    title: '操作',
    dataIndex: 'ACTION',
    fixed: false,
    actions: ({record}) => [
      {
        label: '删除',
        color: 'red',
        onClick: () => handleRemove(record),
      },
    ],
  },
];

const openUploadModal = () => {
  console.log(12321)
  visible.value = true
}

// 暴露方法
defineExpose({
  openUploadModal
})
</script>

<style scoped></style>