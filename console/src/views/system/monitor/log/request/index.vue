<template>
  <div>
    <DynamicTable
        row-key="id"
        header-title="请求日志"
        title-tooltip=""
        :data-request="Api.list"
        :columns="columns"
        bordered
        size="small"
    >
      <template #toolbar>

      </template>
    </DynamicTable>
    <query-drawer ref="queryDrawerRef"/>
    <response-drawer ref="responseDrawerRef"/>
  </div>
</template>

<script lang="ts" setup>
import {getCurrentInstance, ref} from 'vue';
import {useResizeObserver} from '@vueuse/core';
import {baseColumns, type TableColumnItem} from './columns';
import {useTable} from '@/components/business/dynamic-table';
import * as Api from '@/api/backend/logRequest.ts';
import QueryDrawer from "./components/query-drawer.vue";
import ResponseDrawer from "./components/response-drawer.vue";
import {message} from "ant-design-vue";

defineOptions({
  name: 'SystemLogRequest',
});

const [DynamicTable, dynamicTableInstance] = useTable({
  size: 'small',
  rowKey: 'uuid',
  bordered: true,
  autoHeight: true,
});
const currentInstance = getCurrentInstance();

const queryDrawerRef = ref<InstanceType<typeof QueryDrawer>>();
const responseDrawerRef = ref<InstanceType<typeof ResponseDrawer>>();

useResizeObserver(document.documentElement, () => {
  const el = currentInstance?.proxy?.$el as HTMLDivElement;
  if (el) {
    dynamicTableInstance.setProps({
      scroll: {x: window.innerWidth > 2000 ? el.offsetWidth - 20 : 2000},
    });
  }
});

const handleClickQueryItem = (record: TableColumnItem) => {
  queryDrawerRef.value?.open(record)
};

const handleClickResponseItem = (record: TableColumnItem) => {
  responseDrawerRef.value?.open(record)
};

const handleClickRedisItem = () => {
  message.info('暂未实现')
};

const columns: TableColumnItem[] = [
  ...baseColumns,
  {
    title: '操作',
    width: 200,
    dataIndex: 'ACTION',
    hideInSearch: true,
    fixed: 'right',
    actions: ({record}) => [
      {
        label: '请求参数',
        onClick: () => handleClickQueryItem(record),
      },
      {
        label: '响应数据',
        onClick: () => handleClickResponseItem(record),
      },
      {
        label: 'MySQL',
      },
      {
        label: 'Redis',
        onClick: () => handleClickRedisItem(),
      },
    ],
  },
];
</script>