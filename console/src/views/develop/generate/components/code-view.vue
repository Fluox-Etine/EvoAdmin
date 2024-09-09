<template>
  <div>
    <a-alert
        message="温馨提示（代码预览）"
        description="温馨提示，现在还没有什么提示"
        type="success"
        show-icon
    />
    <br>
    <a-card
        style="width: 100%;margin-bottom: 20px"
        :tab-list="tabCodeList"
        :active-tab-key="codeKey"
        @tabChange="key => onCodeTabChange(key)"
    >
      <div v-show="codeKey === 'controller'" class="w-full">
        <highlightjs v-if="code.controller" language="php" :code="code.controller"/>
        <CopyOutlined v-if="code.controller" style="position: fixed; top: 120px; right: 20px;">我是按钮阿</CopyOutlined>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'logic'" class="w-full">
        <highlightjs v-if="code.logic" language="php" :code="code.logic"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'model'" class="w-full">
        <highlightjs v-if="code.model" language="php" :code="code.model"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'validate'" class="w-full">
        <highlightjs v-if="code.validate" language="php" :code="code.validate"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'route'" class="w-full">
        <highlightjs v-if="code.route" language="php" :code="code.route"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'request'" class="w-full">
        <highlightjs v-if="code.request" language="ts" :code="code.request"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'types'" style="width: 100%;">
        <highlightjs v-if="code.types" language="ts" :code="code.types"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'table'" style="width: 100%;">
        <highlightjs v-if="code.table" language="jsx" :code="code.table"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'columns'" style="width: 100%;">
        <highlightjs v-if="code.columns" language="jsx" :code="code.columns"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'form'" style="width: 100%;">
        <highlightjs v-if="code.form" language="jsx" :code="code.form"/>
        <a-empty v-else/>
      </div>
    </a-card>
  </div>
</template>
<script lang="ts" setup>
import {ref} from "vue";
import {CopyOutlined} from '@ant-design/icons-vue';
defineOptions({
  name: 'CodeView',
});
interface CodeType {
  controller?: string;
  logic?: string;
  model?: string;
  validate?: string;
  route?: string;
  request?: string;
  types?: string;
  table?: string;
  columns?: string;
  form?: string
}

defineProps<{ code: CodeType }>();

const tabCodeList = [
  {
    key: 'controller',
    tab: '控制器'
  },
  {
    key: 'logic',
    tab: '逻辑层'
  },
  {
    key: 'model',
    tab: '模型层'
  },
  {
    key: 'validate',
    tab: '验证器'
  },
  {
    key: 'route',
    tab: '后端路由'
  },
  {
    key: 'request',
    tab: '前端请求'
  },
  {
    key: 'types',
    tab: '类型声明'
  },
  {
    key: 'table',
    tab: '数据表格'
  },
  {
    key: 'columns',
    tab: '表格字段'
  },
  {
    key: 'form',
    tab: '表单字段'
  }
];

const codeKey = ref('controller');


/** 切换tab */
const onCodeTabChange = (value: string) => {
  codeKey.value = value;
};
</script>
<style scoped lang="less">

</style>