<script setup lang="ts">
import {computed} from 'vue';
import {configProviderProps} from 'ant-design-vue/es/config-provider/context';
import {merge} from 'lodash-es';
import {ConfigProvider} from 'ant-design-vue';
import {useLayoutSettingStore} from '@/store/modules/layoutSetting';

defineOptions({
  name: 'ProConfigProvider',
});

const props = defineProps(configProviderProps());

const layoutSetting = useLayoutSettingStore();

const theme = computed(() => {
  return merge({}, layoutSetting.themeConfig, props.theme);
});
</script>

<template>
  <ConfigProvider v-bind="$props"  :theme="theme">
    <slot/>
  </ConfigProvider>
</template>
