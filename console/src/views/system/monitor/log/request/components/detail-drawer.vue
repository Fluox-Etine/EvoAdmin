<script setup lang="ts">
import {Drawer, Space, Spin, Descriptions} from 'ant-design-vue';
import {ref} from "vue";
import {formatToDateTime} from "@/utils/dateUtil.ts";
import * as Api from "@/api/backend/logRequest.ts"

defineOptions({
  name: 'DetailDrawer',
});
const loading = ref(false);
const visible = ref(false);
const record = ref<any>();
const type = ref<number>(1)

const recordMap = new Map([
  ['uuid', 'uuid'],
  ['uri', '请求地址'],
  ['method', '请求方式'],
  ['user_agent', '请求头信息'],
  ['ip', '请求IP'],
  ['uid', '操作人'],
  ['_status', '请求状态'],
  ['pid', '进程id'],
  ['exec_time', '执行时间（ms）'],
  ['_created_at', '创建时间'],
] as const);


const recordInfo = ref<Partial<any & { name: string; fsize: string }>>({});

const open = async (options: any, _type: number) => {
  type.value = _type;
  options._created_at = formatToDateTime(options.created_at);
  options._status = options.status === 10 ? '成功' : '失败';
  Array.from(recordMap.keys()).forEach((key) => {
    recordInfo.value[key] = options[key];
  });
  record.value = await Api.detail({id: _type === 3 ? options.uuid : options.id, type: _type});
  visible.value = true;
  loading.value = false;
}

const handleClose = () => {
  visible.value = false;
  recordInfo.value = {};
  record.value = {};
}

defineExpose({open});

</script>

<template>
  <div>
    <Drawer title="日志详情" :width="640" :visible="visible" @close="handleClose" destroyOnClose>
      <Spin :spinning="loading" class="preview-drawer-inner-box">
        <Space direction="vertical">
          <Descriptions bordered :column="1" size="small">
            <template v-for="key in recordMap.keys()" :key="key">
              <Descriptions.Item
                  :label="recordMap.get(key)"
                  :label-style="{ whiteSpace: 'nowrap' }"
              >
                {{ recordInfo[key] }}
              </Descriptions.Item>
            </template>
            <Descriptions.Item label="请求参数" v-if="type === 1">
              <p>{{ record }}</p>
            </Descriptions.Item>
            <Descriptions.Item label="响应数据" v-else-if="type === 2">
              <p>{{ record }}</p>
            </Descriptions.Item>
            <Descriptions.Item label="MySQL日志" v-else-if="type === 3">
              <a-card v-show="record.length"
                      style="width: 100%;margin-bottom: 8px;" v-for="(item,index) in record"
                      :key="index">
                <p>SQL语句：{{ item.sql }}</p>
                <p>执行参数：{{ item.bindings }}</p>
                <p>执行耗时：{{ item.exec_time }} ms</p>
                <p>执行时间：{{ formatToDateTime(item.created_at) }}</p>
              </a-card>
              <a-empty v-show="!record.length" description="MySQL日志正在生成中，请稍后查看"></a-empty>
            </Descriptions.Item>
          </Descriptions>
        </Space>
      </Spin>
    </Drawer>
  </div>
</template>

<style scoped lang="less">

</style>