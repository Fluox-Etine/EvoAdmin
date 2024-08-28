<template>
  <div>
    <a-alert
        message="系统性bug"
        description="为了做字段安全性问题，数据库字段是蛇形，但是我想要前端传递小驼峰，但是前后字段不一致。间接性导致系统管理部分添加和编辑问题。周末系统性测试和系统性修复前后字段保持一致（全部蛇形）。"
        type="error"
        show-icon
    />
  </div>
</template>
<script lang="ts" setup>

</script>

