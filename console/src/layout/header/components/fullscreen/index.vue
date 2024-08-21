<template>
    <Tooltip :title="getTitle" placement="bottom" :mouse-enter-delay="0.5">
    <span @click="toggle">
      <FullscreenOutlined v-if="!isFullscreen"/>
      <FullscreenExitOutlined v-else/>
    </span>
    </Tooltip>
</template>
<script lang="ts" setup>
import {computed, unref} from 'vue';
import {useFullscreen} from '@vueuse/core';

import {FullscreenExitOutlined, FullscreenOutlined} from '@ant-design/icons-vue';
import {Tooltip} from 'ant-design-vue';

const {toggle, isFullscreen} = useFullscreen();

const getTitle = computed(() => {
    return unref(isFullscreen)
        ? '退出全屏'
        : '全屏';
});
</script>
