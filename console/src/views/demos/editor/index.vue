<template>
  <div>
    <a-card header="基础使用" shadow="never" class="!border-none">
      <editor v-model="state.value1" height="500px"/>
    </a-card>
<!--    <a-card header="简洁模式" shadow="never" class="!border-none mt-4">-->
<!--      <editor v-model="state.value2" height="500px" mode="simple"/>-->
<!--    </a-card>-->
  </div>
</template>
<script lang="ts" setup>
import {reactive} from "vue";

const state = reactive({
  value1: '',
  value2: ''
})
</script>