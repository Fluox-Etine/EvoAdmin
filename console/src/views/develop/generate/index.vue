<template>
  <div>
    <a-card
        class="w-full"
        style="margin-bottom: 20px"
        title="代码生成器"
        :tab-list="tabBaseList"
        :active-tab-key="baseKey"
        @tabChange="key => onBaseTabChange(key)"
    >
      <template #extra>
        <a-button type="primary" :icon="h(CodeOutlined)" @click="handleStart">开始生成</a-button>
      </template>
      <a-form :model="formState">
        <div v-show="baseKey === 'table'" class="w-full">
          <TableSheet :code='codeData' :handleCheckTable="fetchTableDetailData"></TableSheet>
        </div>

        <div v-show="baseKey === 'base'" class="w-full">
          <a-alert
              message="温馨提示（基础配置）"
              description="Github地址：https://github.com/Fluox-Etine/EvoAdmin"
              type="success"
              show-icon
          />
          <br>
          <br>
          <a-row :gutter="[16,64]">
            <a-col :span="12">
              <a-form-item label="表名称" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.tableName" disabled/>
              </a-form-item>
              <a-form-item label="表主键" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.PK"/>
              </a-form-item>
              <a-form-item label="模块名" tooltip="模块名，例如：user，生成文件路径为：api / user" :labelCol="{ span: 4 }"
                           :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.moduleName"/>
              </a-form-item>
              <a-form-item label="类目录" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }"
                           tooltip="生成代码文件放在的目录，例如：api，生成文件路径为：api / test；不填写时，默认为对应根目录">
                <a-input v-model:value="formState.classDir"/>
              </a-form-item>
              <a-form-item label="菜单名称" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.menuName"/>
              </a-form-item>
            </a-col>
            <a-col :span="12">
              <a-form-item label="表描述" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.tableComment"/>
              </a-form-item>
              <a-form-item label="删除方式" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }">
                <a-radio-group v-model:value="formState.deleteType">
                  <a-radio :value="1">软删除</a-radio>
                  <a-radio :value="2">物理删除</a-radio>
                </a-radio-group>
              </a-form-item>
              <a-form-item label="类名称" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }"
                           tooltip="生成代码文件名，例如 填写Test,生成文件描述为TestController、TestLogic、TestModel">
                <a-input v-model:value="formState.upperCameName"/>
              </a-form-item>
              <a-form-item label="类注释" tooltip="类注释，例如：注释信息" :labelCol="{ span: 4 }"
                           :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.classComment"/>
              </a-form-item>
              <a-form-item label="菜单构建" tooltip="自动构建：自动执行生成菜单sql。手动添加：自行添加菜单。"
                           :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }">
                <a-radio-group v-model:value="formState.menuBuild">
                  <a-radio :value="1">自行构建</a-radio>
                  <a-radio :value="2">手动构建</a-radio>
                  <a-radio :value="3">不构建</a-radio>
                </a-radio-group>
              </a-form-item>
            </a-col>
          </a-row>
        </div>

        <div v-show="baseKey === 'field'" class="w-full">
          <a-alert
              message="温馨提示（字段配置）"
              description="Gitee地址：https://gitee.com/old-friends-come-again/EvoAdmin"
              type="success"
              show-icon
          />
          <br>
          <a-table :dataSource="dataFieldsSource"
                   :columns="columns"
                   :scroll="{ x: 1024 }"
                   :pagination="false"
          >
            <template #headerCell="{ column }">
              <template v-if="column.dataIndex === 'LIST'">
                {{ column.title }}
                <a-tooltip placement="bottom">
                  <template #title>
                    <span>全选 / 全不选</span>
                  </template>
                  <a-checkbox :checked="allChecked.list" @change="handleAllChange('list')"/>
                </a-tooltip>
              </template>
              <template v-else-if="column.dataIndex === 'CREATE'">
                {{ column.title }}
                <a-tooltip placement="bottom">
                  <template #title>
                    <span>全选 / 全不选</span>
                  </template>
                  <a-checkbox :checked="allChecked.create" @change="handleAllChange('create')"/>
                </a-tooltip>
              </template>
              <template v-else-if="column.dataIndex === 'UPDATE'">
                {{ column.title }}
                <a-tooltip placement="bottom">
                  <template #title>
                    <span>全选 / 全不选</span>
                  </template>
                  <a-checkbox :checked="allChecked.update" @change="handleAllChange('update')"/>
                </a-tooltip>
              </template>
              <template v-else-if="column.dataIndex === 'DETAIL'">
                {{ column.title }}
                <a-tooltip placement="bottom">
                  <template #title>
                    <span>全选 / 全不选</span>
                  </template>
                  <a-checkbox :checked="allChecked.detail" @change="handleAllChange('detail')"/>
                </a-tooltip>
              </template>
              <template v-else-if="column.dataIndex === 'FILTER'">
                {{ column.title }}
                <a-tooltip placement="bottom">
                  <template #title>
                    <span>全选 / 全不选</span>
                  </template>
                  <a-checkbox :checked="allChecked.filter" @change="handleAllChange('filter')"/>
                </a-tooltip>
              </template>
            </template>
            <template v-slot:bodyCell="{ column, record,index}">
              <!--              序列-->
              <template v-if="column.dataIndex === 'dataIndex'">
                {{ index + 1 }}
              </template>

              <template v-else-if="column.dataIndex === 'COLUMN_COMMENT'">
                <a-input v-model:value="record.COLUMN_COMMENT" placeholder="这个作为验证字段描述"/>
              </template>
              <!--              必填-->
              <template v-else-if="column.dataIndex === 'IS_NULLABLE'">
                {{ record.IS_NULLABLE === 1 ? '是' : '否' }}
              </template>

              <!--              列表操作-->
              <template v-else-if="column.dataIndex === 'LIST'">
                <a-checkbox :checked="record.LIST === 1" @change="handleListChange(record)"/>
              </template>

              <!--              创建操作-->
              <template v-else-if="column.dataIndex === 'CREATE'">
                <a-checkbox :checked="record.CREATE === 1" @change="handleCrateChange(record)"/>
              </template>

              <!--              更新操作-->
              <template v-else-if="column.dataIndex === 'UPDATE'">
                <a-checkbox :checked="record.UPDATE === 1" @change="handleUpdateChange(record)"/>
              </template>

              <!--              详情操作-->
              <template v-else-if="column.dataIndex === 'DETAIL'">
                <a-checkbox :checked="record.DETAIL === 1" @change="handleDetailChange(record)"/>
              </template>

              <!--              列表筛选-->
              <template v-else-if="column.dataIndex === 'FILTER'">
                <a-checkbox :checked="record.FILTER === 1" @change="handleFilterableChange(record)"/>
              </template>

              <!--              查询方式-->
              <template v-else-if="column.dataIndex === 'QUERY_TYPE'">
                <a-select
                    allowClear
                    v-model:value="record.QUERY_TYPE"
                    style="width: 120px"
                >
                  <a-select-option value="=">=</a-select-option>
                  <a-select-option value="!=">!=</a-select-option>
                  <a-select-option value=">">></a-select-option>
                  <a-select-option value=">=">>=</a-select-option>
                  <a-select-option value="<">&lt;</a-select-option>
                  <a-select-option value="<=">&lt;=</a-select-option>
                  <a-select-option value="LIKE">LIKE</a-select-option>
                  <a-select-option value="IN">IN</a-select-option>
                  <a-select-option value="NOT IN">NOT IN</a-select-option>
                  <a-select-option value="BETWEEN">BETWEEN</a-select-option>
                </a-select>
              </template>

              <template v-else-if="column.dataIndex === 'PAGE_CONTROL'">
                <a-select
                    v-model:value="record.PAGE_CONTROL"
                    style="width: 120px"
                >
                </a-select>
              </template>

              <template v-else-if="column.dataIndex === 'VALIDATE'">
                <a-select
                    mode="multiple"
                    :max-tag-count="1"
                    v-model:value="record.VALIDATE"
                    style="width: 120px"
                >
                  <a-select-option :value="1">必填项校验</a-select-option>
                  <a-select-option :value="2">长度校验</a-select-option>
                  <a-select-option :value="3">数字验证</a-select-option>
                  <a-select-option :value="4">字符串校验</a-select-option>
                  <a-select-option :value="5">邮箱校验</a-select-option>
                  <a-select-option :value="6">手机号校验</a-select-option>
                  <a-select-option :value="7">整数校验</a-select-option>
                  <a-select-option :value="8">字母校验</a-select-option>
                  <a-select-option :value="9">IP校验</a-select-option>
                </a-select>
              </template>
            </template>
          </a-table>
        </div>

        <div v-show="baseKey === 'gen'" class="w-full">
          <a-alert
              message="温馨提示（生成配置）"
              description="温馨提示，现在还没有什么提示"
              type="success"
              show-icon
          />
          <br>
          <a-row :gutter="[16,32]">
            <a-divider orientation="left" style="border-color: #7cb305" dashed>后端代码</a-divider>
            <a-col :span="24">
              <a-space>
                验证器：
                <a-checkbox v-model:checked="formState.gen.validate.create">创建</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.validate.update">更新</a-checkbox>
              </a-space>
            </a-col>
            <a-col :span="24">
              <a-space>
                控制器：
                <a-checkbox v-model:checked="formState.gen.controller.list">列表</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.controller.create">创建</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.controller.update">更新</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.controller.deleted">删除</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.controller.detail">详情</a-checkbox>
              </a-space>
            </a-col>
            <a-col :span="24">
              <a-space>
                逻辑层：
                <a-checkbox v-model:checked="formState.gen.logic.list">列表</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.logic.create">创建</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.logic.update">更新</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.logic.deleted">删除</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.logic.detail">详情</a-checkbox>
              </a-space>
            </a-col>
            <a-col :span="24">
              <a-space>
                模型层：
                <a-checkbox v-model:checked="formState.gen.model">生成基础模型</a-checkbox>
              </a-space>
            </a-col>
            <a-col :span="24">
              <a-space>
                分页方式：
                <a-radio-group v-model:value="formState.gen.paginate">
                  <a-radio :value="true">分页列表</a-radio>
                  <a-radio :value="false">不分页列表</a-radio>
                </a-radio-group>
              </a-space>
            </a-col>
            <a-divider orientation="right" style="border-color: #7cb305" dashed>前端代码</a-divider>
            <a-col :span="24">
              <a-space>
                请求方法：
                <a-checkbox v-model:checked="formState.gen.request.list">列表</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.request.create">创建</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.request.update">更新</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.request.deleted">删除</a-checkbox>
                <a-checkbox v-model:checked="formState.gen.request.detail">详情</a-checkbox>
              </a-space>
            </a-col>
            <a-col :span="24">
              <a-space>
                表格控件：
                <a-checkbox v-model:checked="formState.gen.page.table">表格</a-checkbox>
              </a-space>
            </a-col>
            <a-col :span="24">
              <a-space>
                创建控件：
                <a-radio-group v-model:value="formState.gen.page.create">
                  <a-radio :value="true">创建Modal</a-radio>
                  <a-radio :value="false">创建页面</a-radio>
                </a-radio-group>
              </a-space>
            </a-col>
            <a-col :span="24">
              <a-space>
                更新控件：
                <a-radio-group v-model:value="formState.gen.page.update">
                  <a-radio :value="true">更新Modal</a-radio>
                  <a-radio :value="false">更新页面</a-radio>
                </a-radio-group>
              </a-space>
            </a-col>
            <a-col :span="24">
              <a-space>
                详情控件：
                <a-radio-group v-model:value="formState.gen.page.detail">
                  <a-radio :value="true">详情Modal</a-radio>
                  <a-radio :value="false">详情页面</a-radio>
                </a-radio-group>
              </a-space>
            </a-col>
          </a-row>
        </div>

        <div v-show="baseKey === 'relation'" class="w-full">
          <a-alert
              message="温馨提示（关联配置）"
              description="暂时不做，这个不重要"
              type="success"
              show-icon
          />
          <br>
        </div>

        <div v-show="baseKey === 'code'" class="w-full">
          <CodeView :code='codeData'></CodeView>
        </div>
      </a-form>
    </a-card>

  </div>
</template>
<script lang="ts" setup>
import {h, reactive, ref, type UnwrapRef} from 'vue';
import {CodeOutlined} from '@ant-design/icons-vue';
import * as Api from '@/api/backend/gen'
import {message as $message} from "ant-design-vue/es/components";
import CodeView from "./modules/codeView.vue";
import TableSheet from "./modules/tableSheet.vue";

const tabBaseList = [
  {
    key: 'table',
    tab: '数据表配置'
  },
  {
    key: 'base',
    tab: '基础配置',
  },
  {
    key: 'field',
    tab: '字段配置',
  },
  {
    key: 'gen',
    tab: '生成配置',
  },
  {
    key: 'relation',
    tab: '关联配置',
  },
  {
    key: 'code',
    tab: `代码预览`
  }
];

const columns = [
  {
    title: '字段名称',
    dataIndex: 'COLUMN_NAME',
    fixed: 'left'
  },
  {
    title: '字段描述',
    dataIndex: 'COLUMN_COMMENT',
    ellipsis: true,
  },
  {
    title: '物理类型',
    dataIndex: 'DATA_TYPE',
    width: 100,
  },
  {
    title: '必填',
    dataIndex: 'IS_NULLABLE',
    width: 50,
  },
  {
    title: '列表操作',
    dataIndex: 'LIST',
    width: 70,
  },
  {
    title: '创建操作',
    dataIndex: 'CREATE',
    width: 70,
  },
  {
    title: '更新操作',
    dataIndex: 'UPDATE',
    width: 70,
  },
  {
    title: '详情操作',
    dataIndex: 'DETAIL',
    width: 70,
  },
  {
    title: '列表筛选',
    dataIndex: 'FILTER',
    width: 70,
  },
  {
    title: '查询方式',
    dataIndex: 'QUERY_TYPE',
    width: 150,
  },
  {
    title: '页面控件',
    dataIndex: 'PAGE_CONTROL',
    width: 150,
  },
  {
    title: '数据字典',
    dataIndex: 'DATA_DICT'
  },
  {
    title: '数据验证',
    dataIndex: 'VALIDATE',
    width: 150,
  }
];

const codeData = ref({
  controller: '',
  logic: '',
  model: '',
  validate: '',
  route: '',
  request: '',
  types: ''
});

const allChecked = ref<any>({
  list: false,
  create: false,
  update: false,
  filter: false,
  detail: false
})

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
  gen: {
    validate: {
      create: true,
      update: true,
    },
    controller: {
      list: true,
      create: true,
      update: true,
      deleted: true,
      detail: false,
    },
    logic: {
      list: true,
      create: true,
      update: true,
      deleted: true,
      detail: false,
    },
    request: {
      list: true,
      create: true,
      update: true,
      deleted: true,
      detail: false,
    },
    model: true,
    paginate: true,
    page: {
      table: true,
      create: true,
      update: true,
      detail: true
    }
  },
});


const baseKey = ref('table');

const dataFieldsSource = ref([]);


/** 切换tab */
const onBaseTabChange = (value: string) => {
  if (value !== 'table' && formState.tableName == '') {
    $message.error('请先选择数据表');
    baseKey.value = 'table';
    return;
  }
  baseKey.value = value;
};

const handleAllChange = (key: string) => {
      console.log(key)
      let checked = false;
      switch (key) {
        case 'list':
          checked = !allChecked.value.list;
          dataFieldsSource.value.forEach((item: any) => {
            if (item.COLUMN_NAME !== 'deleted_at') {
              item.LIST = checked ? 1 : 0;
            }
          });
          allChecked.value.list = checked;
          break;
        case 'create':
          checked = !allChecked.value.create;
          dataFieldsSource.value.forEach((item: any) => {
            if (item.COLUMN_NAME !== 'deleted_at' && item.COLUMN_NAME !== 'created_at' && item.COLUMN_NAME !== 'updated_at' && item.COLUMN_KEY !== 'PRI') {
              item.CREATE = checked ? 1 : 0;
              if (!item.VALIDATE.includes(1)) {
                item.VALIDATE.push(1)
              }
            }
          });
          allChecked.value.create = checked;
          break;
        case'update':
          checked = !allChecked.value.update;
          dataFieldsSource.value.forEach((item: any) => {
            if (item.COLUMN_NAME !== 'deleted_at' && item.COLUMN_NAME !== 'created_at' && item.COLUMN_NAME !== 'updated_at') {
              item.UPDATE = checked ? 1 : 0;
              if (!item.VALIDATE.includes(1)) {
                item.VALIDATE.push(1)
              }
            }
          });
          allChecked.value.update = checked;
          break;
        case'detail':
          checked = !allChecked.value.detail;
          dataFieldsSource.value.forEach((item: any) => {
            if (item.COLUMN_NAME !== 'deleted_at') {
              item.DETAIL = checked ? 1 : 0;
            }
          });
          allChecked.value.detail = checked;
          break;
        case'filter':
          checked = !allChecked.value.filter;
          dataFieldsSource.value.forEach((item: any) => {
            if (item.COLUMN_NAME !== 'deleted_at') {
              item.FILTER = checked ? 1 : 0;
            }
          });
          allChecked.value.filter = checked;
          break;
      }
    }
;

/** 列表操作 */
const handleListChange = (record: any) => {
  record.LIST = record.LIST ? 0 : 1;
};

/** 创建操作 */
const handleCrateChange = (record: any) => {
  record.CREATE = record.CREATE ? 0 : 1;
};


/** 更新操作 */
const handleUpdateChange = (record: any) => {
  record.UPDATE = record.UPDATE ? 0 : 1;
};


/** 详情操作 */
const handleDetailChange = (record: any) => {
  record.DETAIL = record.DETAIL ? 0 : 1;
};


/** 列表筛选 */
const handleFilterableChange = (record: any) => {
  record.FILTER = record.FILTER ? 0 : 1;
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
  console.log(fields)
  baseKey.value = 'base';
}

const handleStart = async () => {
  if (!formState.PK) {
    $message.error('请填写数据表主键');
    return
  }
  // 拦截数据
  if (!formState.upperCameName) {
    baseKey.value = 'base';
    $message.error('请填写类名');
    return
  }
  let data = {
    ...formState,
    fields: dataFieldsSource.value
  }
  const response = await Api.gen(data);
  const {controller, logic, model, validate, route, request, types} = response;
  Object.assign(codeData.value, {controller, logic, model, validate, route, request, types});
  baseKey.value = 'code';
}
</script>

