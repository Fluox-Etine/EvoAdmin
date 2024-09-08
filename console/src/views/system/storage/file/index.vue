<template>
  <div>
    <DynamicTable
        row-key="id"
        header-title="文件资源"
        title-tooltip=""
        :data-request="Api.list"
        :columns="columns"
        bordered
        size="small"
    >
      <template #toolbar>
        <a-button type="dashed" danger @click="handleUpload(10)">
          上传图片
        </a-button>
        <a-button type="dashed" danger @click="handleUpload(20)">
          上传视频
        </a-button>
        <a-button type="dashed" danger @click="handleUpload(30)">
          上传文件
        </a-button>
      </template>
    </DynamicTable>
    <FilePreviewDrawer ref="previewDrawerRef"/>
    <FileModal ref="FilesModal" :multiple="false" @handleCancel="handleCancel"/>
  </div>
</template>

<script lang="ts" setup>
import {getCurrentInstance, ref} from 'vue';
import {useResizeObserver} from '@vueuse/core';
import {baseColumns, type TableColumnItem} from './columns';
import {useTable} from '@/components/business/dynamic-table';
import * as Api from '@/api/backend/uploadFile';
import FilePreviewDrawer from "./components/file-preview-drawer.vue";

defineOptions({
  name: 'UploadFile',
});

const [DynamicTable, dynamicTableInstance] = useTable({
  size: 'small',
  rowKey: 'id',
  bordered: true,
  autoHeight: true,
});
const currentInstance = getCurrentInstance();
const previewDrawerRef = ref<InstanceType<typeof FilePreviewDrawer>>();
const FilesModal = ref<any>();

useResizeObserver(document.documentElement, () => {
  const el = currentInstance?.proxy?.$el as HTMLDivElement;
  if (el) {
    dynamicTableInstance.setProps({
      scroll: {x: window.innerWidth > 2000 ? el.offsetWidth - 20 : 2000},
    });
  }
});

const handleClickFileItem = (record: TableColumnItem) => {
  previewDrawerRef.value?.open(record.id)
};

const handleUpload = (type: number) => {
  FilesModal.value.openFileModal(type, true, 100, 0);
}

const handleCancel = () => {
  dynamicTableInstance?.reload();
}
// 删除方法
const delRowConfirm = async (record: TableColumnItem) => {
  await Api.deleted({id: record.id});
  dynamicTableInstance.reload();
};

const columns: TableColumnItem[] = [
  ...baseColumns,
  {
    title: '操作',
    width: 100,
    dataIndex: 'ACTION',
    hideInSearch: true,
    fixed: 'right',
    actions: ({record}) => [
      {
        label: '详情',
        auth: {
          perm: 'upload:file:detail',
          effect: 'disable',
        },
        onClick: () => handleClickFileItem(record),
      },
      {
        label: '删除',
        auth: {
          perm: 'upload:file:delete',
          effect: 'disable',
        },
        popConfirm: {
          title: '你确定要删除（服务器资源文件）吗？',
          placement: 'left',
          onConfirm: () => delRowConfirm(record),
        },
      },
    ]
  },
];
</script>