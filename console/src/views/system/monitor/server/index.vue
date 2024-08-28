<template>
  <a-row :gutter="[16,32]">
    <a-col :span="12">
      <a-card title="服务器信息" :bordered="false">
        <p>操作系统： </p>
        <p>CPU核心数：</p>
        <p>CPU使用率：</p>
        <p>空闲率：</p>
        <p>缓存：</p>
      </a-card>
    </a-col>
    <a-col :span="12">
      <a-card title="内存" :bordered="false">
        <p>总内存：</p>
        <p>已使用内存：</p>
        <p>空闲内存：</p>
        <p>使用率：</p>
        <p>PHP使用内存：</p>
      </a-card>
    </a-col>
    <a-col :span="12">
      <a-card title="磁盘" :bordered="false">
        <p>总空间：</p>
        <p>已使用空间：</p>
        <p>可用空间：</p>
        <p>使用率：</p>
        <p>PHP使用空间：</p>
      </a-card>
    </a-col>
    <a-col :span="12">
      <a-card title="程序环境" :bordered="false">
        <p>PHP版本：</p>
        <p>Workerman版本：</p>
        <p>程序路径：</p>
        <p>启动时间：</p>
        <p>运行时长：</p>
      </a-card>
    </a-col>
  </a-row>
</template>

<script setup lang="ts">

</script>
<style scoped lang="less">

</style>