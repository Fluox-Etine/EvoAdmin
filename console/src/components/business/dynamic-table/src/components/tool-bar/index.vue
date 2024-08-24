<template>
  <div class="flex justify-between p-16px">
    <div class="flex">
      <slot name="headerTitle">
        <div class="title">
          {{ title }}
          <BasicHelp v-if="titleTooltip" class="ml-6px pt-3px" :text="titleTooltip"/>
        </div>
      </slot>
      <slot name="afterHeaderTitle"/>
    </div>
    <div>
      <Space>
        <slot name="toolbar"/>
      </Space>
      <Divider v-if="$slots.toolbar && showTableSetting" type="vertical"/>
      <TableSetting v-if="showTableSetting"/>
    </div>
  </div>
</template>

<script lang="ts" setup>
import {Divider, Space} from 'ant-design-vue';
import TableSetting from '../table-settings/index.vue';
import BasicHelp from '@/components/core/basic-help/index.vue';

defineOptions({
  name: 'ToolBar',
});

defineProps({
  title: {
    type: String,
    default: '',
  },
  exportFileName: {
    type: String,
    default: '',
  },
  titleTooltip: {
    type: String,
    default: '',
  },
  showTableSetting: {
    type: Boolean,
    default: true,
  },
});

</script>

<style lang="less" scoped>
.title {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  font-size: 16px;
  font-weight: 500;
}
</style>
