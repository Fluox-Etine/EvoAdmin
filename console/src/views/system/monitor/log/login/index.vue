<template>
  <DynamicTable
      row-key="id"
      header-title="登录日志"
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
import {baseColumns, type TableColumnItem} from './columns.tsx';
import {useTable} from '@/components/business/dynamic-table';
import * as Api from '@/api/backend/logLogin.ts';

defineOptions({
  name: 'SystemLoginLog',
});

const [DynamicTable] = useTable({
  size: 'small',
  rowKey: 'id',
  bordered: true,
  autoHeight: true,
});

const columns: TableColumnItem[] = [
  ...baseColumns,

];
</script>