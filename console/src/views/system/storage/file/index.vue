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
        :row-selection="rowSelection"
    >
      <template v-if="isCheckRows" #title>
        <Alert class="w-full" type="info" show-icon>
          <template #message>
            已选 {{ isCheckRows }} 项
            <a-button type="link" @click="rowSelection.selectedRowKeys = []">取消选择</a-button>
          </template>
        </Alert>
      </template>
      <template #toolbar>
        <a-button
            type="error"
            :disabled="!isCheckRows || !$auth('upload:file:delete')"
            @click="delRowConfirm(rowSelection.selectedRowKeys)"
        >
          <Icon icon="ant-design:delete-outlined"/>
          批量删除
        </a-button>
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
import {Alert} from 'ant-design-vue';
import {computed, getCurrentInstance, ref} from 'vue';
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

const rowSelection = ref({
  selectedRowKeys: [] as number[],
  onChange: (selectedRowKeys: number[]) => {
    rowSelection.value.selectedRowKeys = selectedRowKeys;
  },
});

// 是否勾选了表格行
const isCheckRows = computed(() => rowSelection.value.selectedRowKeys.length);
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
const delRowConfirm = async (id: number | number[]) => {
  await Api.deleted({id});
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
          onConfirm: () => delRowConfirm(record.id),
        },
      },
    ]
  },
];
</script>