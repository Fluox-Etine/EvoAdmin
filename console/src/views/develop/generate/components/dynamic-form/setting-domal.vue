<template>
  <a-modal v-model:open="open" :width="985" title="控件设置">
    <a-card
        :active-tab-key="key"
        :tab-list="tabList"
        style="width: 100%;margin-bottom: 20px"
        @tabChange="key => onTabChange(key)">
      <a-form :model="formState">
        <div v-show="key === 'base'" class="w-full">
          <a-form-item :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }" label="字段名" tooltip="字段名，例如：用户名">
            <a-input v-model:value="formState.labelName" placeholder="请输入"/>
          </a-form-item>
          <a-form-item :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }" label="列宽度"
                       tooltip="列宽度，栅栏宽度，例如：12，最小3，最大24">
            <a-input-number v-model:value="formState.colPropsSpan" max="24" min="3" placeholder="请输入"/>
          </a-form-item>
          <a-form-item :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }" label="默认值" tooltip="默认值，例如：admin">
            <a-input v-model:value="formState.defaultValue" placeholder="请输入"/>
          </a-form-item>
          <a-form-item :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }" label="后缀"
                       tooltip="后缀（空就不显示），例如：@qq.com，">
            <a-input v-model:value="formState.afterSlot" placeholder="请输入"/>
          </a-form-item>
          <a-form-item :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }" label="提示信息"
                       tooltip="提示信息，例如：请输入用户名">
            <a-input v-model:value="formState.helpMessage" placeholder="请输入"/>
          </a-form-item>
          <a-form-item :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }" label="是否必填" tooltip="是否必填，例如：true">
            <a-switch v-model:checked="formState.required"/>
          </a-form-item>
        </div>

        <div v-show="key === 'dataSource'" class="w-full">
          <a-form-item :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }" label="数据源类型"
                       tooltip="数据源类型，例如：本地生成">
            <a-select v-model:value="formState.dataSourceType" placeholder="请选择">
              <a-select-option :value="1">本地生成</a-select-option>
              <a-select-option :value="2">远程接口</a-select-option>
            </a-select>
          </a-form-item>
          <a-form-item v-if="formState.dataSourceType === 1" :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }"
                       label="本地生成"
                       tooltip="数据源，例如：['男','女']">
            <a-space
                v-for="(dataSource, index) in formState.dataSource"
                :key="dataSource.id"
                align="baseline"
                style="display: flex; margin-bottom: 8px"
            >
              <a-form-item
                  :name="['dataSource', index, 'label']"
                  :rules="{
                  required: true,
                  message: '例如:男',
                  }"
              >
                <a-input v-model:value="dataSource.label" placeholder="请填写label值"/>
              </a-form-item>
              <a-form-item
                  :name="['dataSource', index, 'value']"
                  :rules="{
                  required: true,
                  message: '例如:1',
          }"
              >
                <a-input v-model:value="dataSource.value" placeholder="请填写value值"/>
              </a-form-item>
              <MinusCircleOutlined @click="removeDataSource(dataSource)"/>
            </a-space>
            <a-form-item>
              <a-button block type="dashed" @click="addDataSource">
                <PlusOutlined/>
                添加数据源
              </a-button>
            </a-form-item>
          </a-form-item>
          <a-form-item v-else :labelCol="{ span: 3 }" :wrapperCol="{ span: 18 }" label="接口地址"
                       tooltip="方法自己实现">
          </a-form-item>
        </div>
      </a-form>
    </a-card>
  </a-modal>
</template>

<script lang="ts" setup>

import {reactive, ref, type UnwrapRef} from "vue";
import {MinusCircleOutlined, PlusOutlined} from '@ant-design/icons-vue';

defineOptions({
  name: 'SettingDomal',
});

const tabList = [
  {
    key: 'base',
    tab: '基础设置'
  },
  {
    key: 'dataSource',
    tab: '数据源'
  }
];
const key = ref('base');

const open = ref<boolean>(false);

interface DataSource {
  first: string;
  last: string;
  id: number;
}

const formState: UnwrapRef<any> = reactive<{ dataSource: DataSource[] }>({
  labelName: '', // label 名词
  colPropsSpan: 12, // 列宽度
  defaultValue: '', // 默认值
  afterSlot: '', // 带后缀
  helpMessage: '', // 提示信息
  required: true, // 是否必填
  dataSourceType: 1, // 1 本地生成 2 远程接口
  dataSource: [] // 数据源

});

const removeDataSource = (item: any) => {
  const index = formState.dataSource.indexOf(item);
  if (index !== -1) {
    formState.dataSource.splice(index, 1);
  }
};
const addDataSource = () => {
  formState.dataSource.push({
    label: '',
    value: '',
    id: Date.now(),
  });
};
/** 切换tab */
const onTabChange = (value: string) => {
  key.value = value;
};

/** 打开弹窗 */
const openSettingModal = () => {
  open.value = true
}


// 暴露方法
defineExpose({
  openSettingModal
})
</script>
