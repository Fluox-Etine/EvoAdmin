<template>
  <div>
    <a-card
        class="w-full"
        style="margin-bottom: 20px"
        title="字典生成器"
        :tab-list="tabBaseList"
        :active-tab-key="baseKey"
        @tabChange="key => onBaseTabChange(key)"
    >
      <template #extra>
        <a-button type="primary" :icon="h(CodeOutlined)" @click="handleStart">开始生成</a-button>
      </template>
      <a-form :model="formState">
        <div v-show="baseKey === 'base'" class="w-full">
          <a-alert
              message="温馨提示（基础配置）"
              description="Github地址：https://github.com/Fluox-Etine/EvoAdmin"
              type="success"
              show-icon
          />
          <br>
          <br>
        </div>
      </a-form>
    </a-card>
  </div>
</template>
<script lang="ts" setup>
import {h, reactive, ref, type UnwrapRef} from 'vue';
import {CodeOutlined} from '@ant-design/icons-vue';
import * as Api from '@/api/backend/gen'
import {message} from "ant-design-vue/es/components";

const tabBaseList = [
  {
    key: 'table',
    tab: '数据配置'
  },
  {
    key: 'code',
    tab: `代码预览`
  }
];


const codeData = ref({
  controller: '',
  logic: '',
  model: '',
  validate: '',
  route: '',
  request: '',
  types: '',
  table: '',
  columns: '',
  form: ''
});


const formState: UnwrapRef<any> = reactive({
  tableName: '',
  tableComment: '',
  PK: '',
  classDir: '',
  deleteType: 1,
  menuName: '',
  tableDesc: '',
  moduleName: 'admin',
  upperCameName: '',
  menuBuild: 1,
});

const baseKey = ref('table');

const dataFieldsSource = ref([]);

/** 切换tab */
const onBaseTabChange = (value: string) => {
  if (value !== 'table' && formState.tableName == '') {
    // message.error('请先选择数据表');
    // baseKey.value = 'table';
    // return;
    fetchTableDetailData('evo_test_goods');
  }
  baseKey.value = value;
};


/** 获取表信息 */
const fetchTableDetailData = async (tableName: string) => {
  const {fields, table} = await Api.tableDetail({tableName: tableName});
  formState.tableName = table.Name;
  formState.tableComment = table.Comment;
  formState.PK = table.PK;
  formState.classComment = table.Comment;
  formState.upperCameName = table.upperCameName;
  formState.classDir = table.classDir;
  dataFieldsSource.value = fields;
  baseKey.value = 'base';
}

/** 生成代码 */
const handleStart = async () => {
  if (!formState.PK) {
    message.error('请填写数据表主键');
    return
  }
  // 拦截数据
  if (!formState.upperCameName) {
    baseKey.value = 'base';
    message.error('请填写类名');
    return
  }
  let data = {
    ...formState,
    fields: dataFieldsSource.value
  }
  const response = await Api.gen(data);
  const {controller, logic, model, validate, route, request, types, table, columns, form} = response;
  Object.assign(codeData.value, {controller, logic, model, validate, route, request, types, table, columns, form});
  baseKey.value = 'code';
}

</script>

