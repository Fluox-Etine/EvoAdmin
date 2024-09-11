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
    <detail-drawer ref="detailDrawerRef"/>
  </div>
</template>

<script lang="ts" setup>
import {getCurrentInstance, ref} from 'vue';
import {useResizeObserver} from '@vueuse/core';
import {baseColumns, type TableColumnItem} from './columns';
import {useTable} from '@/components/business/dynamic-table';
import * as Api from '@/api/backend/logRequest.ts';
import {message} from "ant-design-vue";
import DetailDrawer from "./components/detail-drawer.vue";

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

const detailDrawerRef = ref<InstanceType<typeof DetailDrawer>>();

useResizeObserver(document.documentElement, () => {
  const el = currentInstance?.proxy?.$el as HTMLDivElement;
  if (el) {
    dynamicTableInstance.setProps({
      scroll: {x: window.innerWidth > 2000 ? el.offsetWidth - 20 : 2000},
    });
  }
});

const handleClickDetailItem = (record: TableColumnItem, type: number) => {
  detailDrawerRef.value?.open(record, type)
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
        onClick: () => handleClickDetailItem(record, 1),
      },
      {
        label: '响应数据',
        onClick: () => handleClickDetailItem(record, 2),
      },
      {
        label: 'MySQL',
        onClick: () => handleClickDetailItem(record, 3),
      },
      {
        label: 'Redis',
        onClick: () => handleClickRedisItem(),
      },
    ],
  },
];
</script>