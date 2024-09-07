<template>
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

    </template>
  </DynamicTable>
</template>

<script lang="ts" setup>
import {getCurrentInstance} from 'vue';
import {useResizeObserver} from '@vueuse/core';
import {baseColumns, type TableColumnItem} from './columns';
import {useTable} from '@/components/business/dynamic-table';
import * as Api from '@/api/backend/uploadFile';
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

useResizeObserver(document.documentElement, () => {
  const el = currentInstance?.proxy?.$el as HTMLDivElement;
  if (el) {
    dynamicTableInstance.setProps({
      scroll: {x: window.innerWidth > 2000 ? el.offsetWidth - 20 : 2000},
    });
  }
});

// 删除方法
const delRowConfirm = async (record: TableColumnItem) => {
  await Api.deleted({id: record.id});
  dynamicTableInstance.reload();
};

const columns: TableColumnItem[] = [
  ...baseColumns,
  {
    title: '操作',
    width: 130,
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
        onClick: () => openMenuModal(record),
      },
      {
        label: '删除',
        auth: {
          perm: 'upload:file:delete',
          effect: 'disable',
        },
        onClick: () => delRowConfirm(record),
      },
    ]
  },
];
</script>