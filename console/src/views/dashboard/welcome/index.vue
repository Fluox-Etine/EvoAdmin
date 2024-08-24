<template>
  <div>
    <a-card
        style="width: 100%"
        title="代码生成器"
        :tab-list="tabList"
        :active-tab-key="key"
        @tabChange="key => onTabChange(key)"
    >
      <template #extra>
        <a-button type="primary" :icon="h(CodeOutlined)">开始生成</a-button>
      </template>
      <a-form :model="formState">
        <div v-show="key === 'base'" style="width: 100%;">
          <a-divider orientation="left">基础信息</a-divider>
          <a-row :gutter="[16,64]">
            <a-col :span="12">
              <a-form-item label="表名称" :labelCol="{ span: 3 }" :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.tableName"/>
              </a-form-item>
              <a-form-item label="删除方式" :labelCol="{ span: 3 }" :wrapperCol="{ span: 20 }">
                <a-radio-group v-model:value="formState.deleteType">
                  <a-radio :value="1">软删除</a-radio>
                  <a-radio :value="2">物理删除</a-radio>
                </a-radio-group>
              </a-form-item>
              <a-form-item label="类目录" :labelCol="{ span: 3 }" :wrapperCol="{ span: 20 }"
                           tooltip="生成代码文件放在的目录，例如：api，生成文件路径为：api / test；不填写时，默认为对应根目录">
                <a-input v-model:value="formState.classDesc"/>
              </a-form-item>
              <a-form-item label="菜单名称" :labelCol="{ span: 3 }" :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.menuName"/>
              </a-form-item>
            </a-col>
            <a-col :span="12">
              <a-form-item label="表描述" :labelCol="{ span: 3 }" :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.tableDesc"/>
              </a-form-item>
              <a-form-item label="模块名" tooltip="模块名，例如：user，生成文件路径为：api / user" :labelCol="{ span: 3 }"
                           :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.moduleName"/>
              </a-form-item>
              <a-form-item label="类名称" :labelCol="{ span: 3 }" :wrapperCol="{ span: 20 }"
                           tooltip="生成代码文件名，例如 填写test,生成文件描述为TestController、TestLogic、TestModel">
                <a-input v-model:value="formState.classDesc"/>
              </a-form-item>
              <a-form-item label="菜单构建" tooltip="自动构建：自动执行生成菜单sql。手动添加：自行添加菜单。"
                           :labelCol="{ span: 3 }" :wrapperCol="{ span: 20 }">
                <a-radio-group v-model:value="formState.menuBuild">
                  <a-radio :value="1">自行构建</a-radio>
                  <a-radio :value="2">手动构建</a-radio>
                  <a-radio :value="3">不构建</a-radio>
                </a-radio-group>
              </a-form-item>
            </a-col>
          </a-row>
        </div>
        <div v-show="key === 'field'" style="width: 100%;">
          <a-alert
              message="温馨提示"
              description="温馨提示，现在还没有什么提示"
              type="success"
              show-icon
          />
          <br>
          <a-table :dataSource="dataSource" :columns="columns" :pagination="false" bordered>

            <template v-slot:bodyCell="{ column, record}">
              <template v-if="column.dataIndex === 'name'">
                {{ record }}
              </template>
            </template>
          </a-table>
        </div>
      </a-form>
    </a-card>

  </div>
</template>
<script lang="ts" setup>
import {h, reactive, ref, type UnwrapRef} from 'vue';
import {CodeOutlined} from '@ant-design/icons-vue';

const tabList = [
  {
    key: 'base',
    tab: '基础配置',
  },
  {
    key: 'field',
    tab: '字段配置',
  },
  {
    key: 'menu',
    tab: '菜单配置',
  },
  {
    key: 'relation',
    tab: '关联配置',
  }
];

const columns = [
  {
    title: '姓名',
    dataIndex: 'name'
  },
  {
    title: '年龄',
    dataIndex: 'age'
  },
  {
    title: '住址',
    dataIndex: 'address'
  },
];

const dataSource = [
  {
    key: '1',
    name: '胡彦斌',
    age: 32,
    address: '西湖区湖底公园1号',
  },
  {
    key: '2',
    name: '胡彦祖',
    age: 42,
    address: '西湖区湖底公园1号',
  },
];
const key = ref('base');

const onTabChange = (value: string) => {
  key.value = value;
};

const formState: UnwrapRef<any> = reactive({
  tableName: '',
  tableDesc: '',
  deleteType: 1,
});
</script>

