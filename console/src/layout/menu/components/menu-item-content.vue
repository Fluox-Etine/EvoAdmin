<template>
  <template v-if="item?.meta?.icon">
<!--    <Icon :icon="item.meta.icon" />-->
  </template>
  {{ item?.meta?.title}}
</template>

<script lang="ts" setup>
  import type { RouteRecordRaw } from 'vue-router';
  // import { Icon } from '@/components/basic/icon';

  defineOptions({
    name: 'MenuItemContent',
  });

  defineProps({
    item: {
      type: Object as PropType<RouteRecordRaw>,
      default: () => ({}),
    },
  });
</script>
