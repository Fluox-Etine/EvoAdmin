<template>
  <div>
    <a-alert
        message="温馨提示（数据表配置）"
        description="请点击数据表获取到表数据字段详情"
        type="success"
        show-icon
    />
    <br>
    <div style="width: 100%;">
      <div style="width: 280px;margin: 20px;height: 200px;display: inline-block;" v-for="(item,index) in tableList"
           :key="index">
        <a-card :title="`数据表 ${index + 1}`">
          <template #extra>
            <a-button type="primary" ghost :icon="h(CheckOutlined)" @click="handleCheckTable(item.name)"></a-button>
          </template>
          <p>表名称：{{ item.name }}</p>
          <p>表注释：{{ item.comment }}</p>
          <p>表引擎：{{ item.engine }}</p>
          <p>表创建时间：{{ item.create_time }}</p>
        </a-card>
        <br>
      </div>
    </div>
  </div>
</template>
<script lang="ts" setup>
import {h, ref} from "vue";
import * as Api from "@/api/backend/gen.ts"
import {CheckOutlined, CodeOutlined, RedoOutlined} from "@ant-design/icons-vue";

defineProps({
  handleCheckTable: {
    type: Function
  }
})

const tabTableList = [
  {
    key: 'database',
    tab: '数据库'
  },
  {
    key: 'sql',
    tab: 'SQL语句'
  },
];

const tableKey = ref('database');

const tableList = ref([]);

const SQL = ref<string>('');

/** 切换tab */
const onTableTabChange = (value: string) => {
  tableKey.value = value;
};


/** 获取数据库列表 */
const fetchTableSheetData = async () => {
  const response = await Api.tableSheet();
  tableList.value = response.data;
}
fetchTableSheetData();
</script>