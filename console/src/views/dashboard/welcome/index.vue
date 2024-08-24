<template>
  <div>
    <a-card
        style="width: 100%;margin-bottom: 20px"
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
              <a-form-item label="表名称" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.tableName" disabled/>
              </a-form-item>
              <a-form-item label="删除方式" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }">
                <a-radio-group v-model:value="formState.deleteType">
                  <a-radio :value="1">软删除</a-radio>
                  <a-radio :value="2">物理删除</a-radio>
                </a-radio-group>
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
              <a-form-item label="模块名" tooltip="模块名，例如：user，生成文件路径为：api / user" :labelCol="{ span: 4 }"
                           :wrapperCol="{ span: 20 }">
                <a-input v-model:value="formState.moduleName"/>
              </a-form-item>
              <a-form-item label="类名称" :labelCol="{ span: 4 }" :wrapperCol="{ span: 20 }"
                           tooltip="生成代码文件名，例如 填写test,生成文件描述为TestController、TestLogic、TestModel">
                <a-input v-model:value="formState.upperCameName"/>
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
        <div v-show="key === 'field'" style="width: 100%;">
          <a-alert
              message="温馨提示"
              description="温馨提示，现在还没有什么提示"
              type="success"
              show-icon
          />
          <br>
          <a-table :dataSource="dataFieldsSource"
                   :columns="columns"
                   :scroll="{ x: 1024 }"
                   :pagination="false"
          >
            <template v-slot:bodyCell="{ column, record,index}">
              <!--              序列-->
              <template v-if="column.dataIndex === 'dataIndex'">
                {{ index + 1 }}
              </template>

              <!--              必填-->
              <template v-else-if="column.dataIndex === 'IS_NULLABLE'">
                <a-checkbox :checked="record.IS_NULLABLE === 1" @change="handleIsNullableChange(record)"/>
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
                    ref="select"
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
                    ref="select"
                    v-model:value="record.PAGE_CONTROL"
                    style="width: 120px"
                >
                </a-select>
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
import * as Api from '@/api/backend/gen'

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
    dataIndex: 'COLUMN_TYPE'
  },
  {
    title: '必填',
    dataIndex: 'IS_NULLABLE'
  },
  {
    title: '列表操作',
    dataIndex: 'LIST'
  },
  {
    title: '创建操作',
    dataIndex: 'CREATE'
  },
  {
    title: '更新操作',
    dataIndex: 'UPDATE'
  },
  {
    title: '详情操作',
    dataIndex: 'DETAIL'
  },
  {
    title: '列表筛选',
    dataIndex: 'FILTER'
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
    dataIndex: 'dataDict'
  }
];

const dataFieldsSource = ref([]);

const formState: UnwrapRef<any> = reactive({
  tableName: '',
  tableComment: '',
  classDir: '',
  deleteType: 1,
  menuName: '',
  tableDesc: '',
  moduleName: '',
  upperCameName: '',
  menuBuild: 1
});

const key = ref('field');

/** 切换tab */
const onTabChange = (value: string) => {
  key.value = value;
};

/** 是否必填数据 */
const handleIsNullableChange = (record: any) => {
  record.IS_NULLABLE = record.IS_NULLABLE ? 0 : 1;
};

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

const fetchTableDetailData = async () => {
  const {fields, table} = await Api.tableDetail({tableName: 'evo_test_goods'});
  formState.tableName = table.Name;
  formState.tableComment = table.Comment;
  dataFieldsSource.value = fields;
}

fetchTableDetailData();
</script>

