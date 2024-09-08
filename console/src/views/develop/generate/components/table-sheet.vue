<template>
  <div>
    <a-alert
        message="温馨提示（数据表配置）"
        description="请点击数据表获取到表数据字段详情"
        type="success"
        show-icon
    />
    <br>
    <div class="w-full">
      <div style="width: 280px;margin: 20px;height: 200px;display: inline-block;" v-for="(item,index) in tableList"
           :key="index">
        <a-card :title="`${item.name}`">
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
import {CheckOutlined} from "@ant-design/icons-vue";

defineProps({
  handleCheckTable: {
    type: Function
  }
})
ref('database');
const tableList = ref([]);
ref<string>('');
/** 获取数据库列表 */
const fetchTableSheetData = async () => {
  const response = await Api.tableSheet();
  tableList.value = response.data;
}
fetchTableSheetData();
</script>