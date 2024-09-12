<template>
  <div>
    <a-modal v-model:open="open" :width="985" :title="state.title" @ok="onOk" @cancel="handleCancel">
    </a-modal>
  </div>
</template>

<script setup lang="ts">

import {ref} from "vue";

defineOptions({
  name: 'SettingDomal',
});

const open = ref<boolean>(false);


/** 打开弹窗 */
const openSettingModal = () => {
  open.value = true
}


// 暴露方法
defineExpose({
  openSettingModal
})
</script>
