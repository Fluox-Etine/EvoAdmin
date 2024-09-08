<template>
  <div>
    <a-card title="基础使用" shadow="never" class="!border-none">
      <editor v-model="state.value1" height="500px"/>
      <div class="mt-4" v-html="state.value1"></div>
    </a-card>
    <a-card title="简洁模式" shadow="never" class="!border-none mt-4">
      <editor v-model="state.value2" height="500px" mode="simple"/>
      <div class="mt-4" v-html="state.value2"></div>
    </a-card>
  </div>
</template>
<script lang="ts" setup>
import {reactive} from "vue";

const state = reactive({
  value1: '',
  value2: ''
})
</script>