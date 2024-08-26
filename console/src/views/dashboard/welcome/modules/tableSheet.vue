<template>
  <div>
    <a-alert
        message="温馨提示（数据表配置）"
        description="不建议通过SQL语句分析字段信息，正则匹配有可能会有误，最好导入数据库表再生成"
        type="success"
        show-icon
    />
    <br>
    <a-card
        style="width: 100%;margin-bottom: 20px;height: 100%"
        :tab-list="tabTableList"
        :active-tab-key="tableKey"
        @tabChange="key => onTableTabChange(key)"
    >
      <template #extra>
        <a-button type="primary" ghost :icon="h(RedoOutlined)" @click="fetchTableSheetData()"></a-button>
      </template>
      <div v-show="tableKey === 'database'" style="width: 100%;">
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
      <div v-show="tableKey === 'sql'" style="width: 100%;">
        <a-card title="建表语句">
          <template #extra>
            <a-button type="primary" :icon="h(CodeOutlined)">开始转换</a-button>
          </template>
          <a-textarea :rows="15" v-model:value="SQL" placeholder="建表语句"/>
        </a-card>
      </div>
    </a-card>
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