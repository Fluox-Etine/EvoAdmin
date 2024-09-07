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
      <a-alert message="切片上传单个文件必须大于5MB" type="info" show-icon/>
      <a-upload :multiple="true" :accept="accept" :before-upload="beforeUpload" :show-upload-list="false">
        <a-button type="primary"> 切片上传</a-button>
      </a-upload>
    </a-flex>
    <DynamicTable :search="false" :data-source="fileList" :columns="columns"/>
  </DraggableModal>
</template>

<script setup lang="tsx">
import SparkMD5 from 'spark-md5'
import {computed, ref} from 'vue';
import {message, type UploadProps} from 'ant-design-vue';
import {type FileItem, fileListColumns, UploadResultStatus} from './columns.tsx';
import {DraggableModal} from '@/components/business/draggable-modal/index.ts';
import {type TableColumn, useTable} from '@/components/business/dynamic-table';
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";
import * as Api from '@/api/backend/upload.ts';

const emit = defineEmits(['uploadSuccess']);

const [DynamicTable] = useTable();
const chunkSize = import.meta.env.VITE_CHUNK_SIZE * 1024 * 1024;
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

// 逐个上传文件
  async function uploadNextFile(index = 0) {
    if (index >= uploadFileList.length) {
      return;
    }
    const item = uploadFileList[index];
    try {
      item.status = UploadResultStatus.HASH;
      await chunkUploadFile(item.file, item.name, (progress) => {
        item.percent = progress.percent;
        item.status = progress.status;
      });
    } catch (error) {
      console.log(error)
      item.status = UploadResultStatus.ERROR;
    }
    await uploadNextFile(index + 1);
  }

  await uploadNextFile();
};


/** 切片上传 */
async function chunkUploadFile(file, name, onUploadProgress) {
  const chunks = Math.ceil(file.size / chunkSize);
  const hash = await calculateHash(file);
  onUploadProgress({status: UploadResultStatus.UPLOADING, percent: 1});

  async function uploadChunk(chunkIndex) {
    const start = chunkIndex * chunkSize;
    const end = Math.min(start + chunkSize, file.size);
    const chunk = file.slice(start, end);
    try {
      const chunkPercent = Math.round(((chunkIndex + 1) / (chunks + 1)) * 100);
      onUploadProgress({percent: chunkPercent, status: UploadResultStatus.UPLOADING});
      await Api.uploadChunk({
        chunk: chunk,
        hash: hash,
        index: chunkIndex,
        fileName: name,
      });
      if (chunkIndex < chunks - 1) {
        return uploadChunk(chunkIndex + 1);
      } else {
        onUploadProgress({status: UploadResultStatus.MERGE, percent: 99});
        return mergeChunks();
      }
    } catch (error) {
      console.error(`Error uploading chunk ${chunkIndex}:`, error);
      throw error;
    }
  }

  // 切片合并
  async function mergeChunks() {
    try {
      await Api.chunkMerge({
        hash: hash,
        fileName: name,
        type: fileType.value._value,
        group: groupId.value._value
      });
      onUploadProgress({status: UploadResultStatus.SUCCESS, percent: 100});
    } catch (error) {
      console.error('Error merging chunks:', error);
      throw error;
    }
  }

  return uploadChunk(0);
}

/** 计算文件哈希值 */
const calculateHash = async (file: any) => {
  const fileReader = new FileReader();
  const blobSlice = File.prototype.slice
  const chunks = Math.ceil(file.size / chunkSize);
  let currentChunk = 0;
  const spark = new SparkMD5()

  return new Promise((resolve) => {
    fileReader.onload = (e) => {
      spark.appendBinary(e.target.result) // append binary string
      currentChunk++
      if (currentChunk < chunks) {
        loadNext()
      } else {
        resolve(spark.end())
      }
    }
    fileReader.onerror = () => {
      $message.error(`读取文件${file.name}哈希值失败，上传终止`);
    }
    const loadNext = () => {
      const start = currentChunk * chunkSize
      const end = start + chunkSize >= file.size ? file.size : start + chunkSize
      const chunk = blobSlice.call(file, start, end)
      fileReader.readAsArrayBuffer(chunk) // 使用异步的方式读取分片数据
    }
    loadNext()
  })
}
const beforeUpload: UploadProps['beforeUpload'] = async (file) => {
  if (file.size / 1024 / 1024 < 5) {
    message.error('当前文件小于5MB，请使用普通上传');
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

const openChunkUploadModal = (type: FileTypeEnum, group: number) => {
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
  openChunkUploadModal
})
</script>

<style scoped></style>