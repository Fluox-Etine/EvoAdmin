<script setup lang="ts">
import {ref} from "vue";

const lngLatInfo = ref()
</script>

<template>
  <div>
    {{ lngLatInfo }}
    <a-map v-model="lngLatInfo"/>
  </div>
</template>

<style scoped lang="less">

</style>