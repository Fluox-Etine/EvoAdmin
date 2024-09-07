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
    <a-flex justify="space-between" align="center">
      <a-alert message="普通上传单个文件不超过5MB" type="info" show-icon/>
      <a-upload :multiple="true" :accept="accept" :before-upload="beforeUpload" :show-upload-list="false">
        <a-button type="primary"> 立即上传</a-button>
      </a-upload>
    </a-flex>
    <DynamicTable :search="false" :data-source="fileList" :columns="columns"/>
  </DraggableModal>
</template>

<script setup lang="tsx">
import {computed, ref} from 'vue';
import {message, type UploadProps} from 'ant-design-vue';
import {type FileItem, fileListColumns, UploadResultStatus} from './columns.tsx';
import {DraggableModal} from '@/components/business/draggable-modal/index.ts';
import {type TableColumn, useTable} from '@/components/business/dynamic-table';
import * as Api from '@/api/backend/upload.ts';
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";

const emit = defineEmits(['uploadSuccess']);

const [DynamicTable] = useTable();

const visible = ref(false);
const fileList = ref<FileItem[]>([]);
const fileType = ref<FileTypeEnum>(10);
const groupId = ref<number>(0)
const accept = ref<string>('')
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

  async function uploadNextFile(index = 0) {
    if (index >= uploadFileList.length) {
      return;
    }
    const item = uploadFileList[index];
    try {
      await Api.uploadUpload({
        file: item.file,
        fileName: item.name,
        type: fileType.value._value,
        group: groupId.value._value
      }, undefined, {
        onUploadProgress(progressEvent) {
          item.percent = ((progressEvent.loaded / progressEvent.total!) * 100) | 0;
          item.status = UploadResultStatus.UPLOADING;
        },
      });
      item.status = UploadResultStatus.SUCCESS;
      await uploadNextFile(index + 1);
    } catch (error) {
      item.status = UploadResultStatus.ERROR;
      await uploadNextFile(index + 1);
    }
  }

  await uploadNextFile();
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

const openUploadModal = (type: FileTypeEnum, group: number) => {
  if (type._value === FileTypeEnum.IMAGE) {
    accept.value = import.meta.env.VITE_IMAGE_TYPE;
  } else if (type._value === FileTypeEnum.VIDEO) {
    accept.value = import.meta.env.VITE_VIDEO_TYPE;
  } else {
    accept.value = import.meta.env.VITE_FILE_TYPE;
  }
  fileType.value = type
  groupId.value = group
  visible.value = true
}

// 暴露方法
defineExpose({
  openUploadModal
})
</script>

<style scoped></style>