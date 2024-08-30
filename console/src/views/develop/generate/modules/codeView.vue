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
      <div v-show="codeKey === 'controller'" style="width: 100%;">
        <highlightjs v-if="code.controller" language="php" :code="code.controller"/>
        <CopyOutlined v-if="code.controller" style="position: fixed; top: 120px; right: 20px;">我是按钮阿</CopyOutlined>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'logic'" style="width: 100%;">
        <highlightjs v-if="code.logic" language="php" :code="code.logic"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'model'" style="width: 100%;">
        <highlightjs v-if="code.model" language="php" :code="code.model"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'validate'" style="width: 100%;">
        <highlightjs v-if="code.validate" language="php" :code="code.validate"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'route'" style="width: 100%;">
        <highlightjs v-if="code.route" language="php" :code="code.route"/>
        <a-empty v-else/>
      </div>
      <div v-show="codeKey === 'request'" style="width: 100%;">
        <highlightjs v-if="code.request" language="js" :code="code.request"/>
        <a-empty v-else/>
      </div>
    </a-card>
  </div>
</template>
<script lang="ts" setup>
import {ref} from "vue";
import {CopyOutlined} from '@ant-design/icons-vue';

interface CodeType {
  controller?: string;
  logic?: string;
  model?: string;
  validate?: string;
  route?: string;
  request?: string;
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