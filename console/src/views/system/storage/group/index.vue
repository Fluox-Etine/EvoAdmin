<template>
  <DynamicTable
      row-key="id"
      header-title="文件分组"
      title-tooltip=""
      :data-request="Api.list"
      :columns="columns"
      bordered
      size="small"
  >
    <template #toolbar>
      <a-button type="primary" :disabled="!$auth('upload:group:create')" @click="openMenuModal({})">
        新增
      </a-button>
    </template>
  </DynamicTable>
</template>

<script lang="ts" setup>
import {getCurrentInstance} from 'vue';
import {useResizeObserver} from '@vueuse/core';
import {baseColumns, type TableColumnItem} from './columns';
import {formSchemas} from './formSchemas';
import {useTable} from '@/components/business/dynamic-table';
import {useFormModal} from '@/hooks/useModal/';
import * as Api from '@/api/backend/uploadGroup';

defineOptions({
  name: 'UploadGroup',
});

const [DynamicTable, dynamicTableInstance] = useTable({
  pagination: true,
  size: 'small',
  rowKey: 'id',
  bordered: true,
  autoHeight: true,
});
const [showModal] = useFormModal();
const currentInstance = getCurrentInstance();

useResizeObserver(document.documentElement, () => {
  const el = currentInstance?.proxy?.$el as HTMLDivElement;
  if (el) {
    dynamicTableInstance.setProps({
      scroll: {x: window.innerWidth > 2000 ? el.offsetWidth - 20 : 2000},
    });
  }
});

// 添加和编辑方法
const openMenuModal = async (record: Partial<TableListItem>) => {
  const [formRef] = await showModal({
    modalProps: {
      title: `${record.id ? '编辑' : '新增'}操作`,
      width: 700,
      onFinish: async (values) => {
        if (record.id) {
          await Api.update(values);
        } else {
          await Api.create(values);
        }
        dynamicTableInstance.reload();
      },
    },
    formProps: {
      labelWidth: 100,
      schemas: formSchemas(),
    },
  });
};

// 删除方法
const delRowConfirm = async (record: TableListItem) => {
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
        label: '编辑',
        auth: {
          perm: 'upload:group:update',
          effect: 'disable',
        },
        onClick: () => openMenuModal(record),
      },
      {
        label: '删除',
        auth: {
          perm: 'upload:group:delete',
          effect: 'disable',
        },
        onClick: () => delRowConfirm(record),
      },
    ]
  },
];
</script>