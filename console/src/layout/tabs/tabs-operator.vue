<script setup lang="ts">
import {computed, PropType, unref} from 'vue';
  import {
    DownOutlined,
    ReloadOutlined,
    CloseOutlined,
    VerticalRightOutlined,
    VerticalLeftOutlined,
    ColumnWidthOutlined,
    MinusOutlined,
  } from '@ant-design/icons-vue';
  import { useRoute, useRouter, type RouteLocationNormalizedLoaded } from 'vue-router';
  import { isFunction } from 'lodash-es';
  import { message } from 'ant-design-vue';
  import { REDIRECT_NAME } from '@/router/constant';
  import { useTabsViewStore } from '@/store/modules/tabsView';

  defineOptions({
    name: 'TabOperator',
  });

  const props = defineProps({
    tabItem: {
      type: Object as PropType<RouteLocationNormalizedLoaded>,
      required: true,
    },
    isExtra: Boolean,
  });

  const route = useRoute();
  const router = useRouter();
  const tabsViewStore = useTabsViewStore();

  const activeKey = computed(() => tabsViewStore.getCurrentTab?.fullPath);

  /** 标签页列表 */
  const tabsList = computed(() => tabsViewStore.getTabsList);

  /** 目标路由是否等于当前路由 */
  const isCurrentRoute = (route) => {
    return router.currentRoute.value.matched.some((item) => item.name === route.name);
  };

  /** 关闭当前页面 */
  const removeTab = () => {
    if (tabsList.value.length === 1) {
      return message.warning('这已经是最后一页，不能再关闭了！');
    }
    // tabsViewMutations.closeCurrentTabs(route)
    tabsViewStore.closeCurrentTab(props.tabItem);
  };

  /** 刷新页面 */
  const reloadPage = () => {
    router.replace({
      name: REDIRECT_NAME,
      params: {
        path: unref(route).fullPath,
      },
    });
  };

  /** 关闭左侧 */
  const closeLeft = () => {
    // tabsViewMutations.closeLeftTabs(route)
    tabsViewStore.closeLeftTabs(props.tabItem);
    !isCurrentRoute(props.tabItem) && router.replace(props.tabItem.fullPath);
  };

  /** 关闭右侧 */
  const closeRight = () => {
    // tabsViewMutations.closeRightTabs(route)
    tabsViewStore.closeRightTabs(props.tabItem);
    !isCurrentRoute(props.tabItem) && router.replace(props.tabItem.fullPath);
  };

  /** 关闭其他 */
  const closeOther = () => {
    // tabsViewMutations.closeOtherTabs(route)
    tabsViewStore.closeOtherTabs(props.tabItem);
    !isCurrentRoute(props.tabItem) && router.replace(props.tabItem.fullPath);
  };

  /** 关闭全部 */
  const closeAll = () => {
    tabsViewStore.closeAllTabs();
    router.replace('/');
  };

  /** 打开页面所在的文件(仅在开发环境有效) */
  const openPageFile = async () => {

    const routes = router.getRoutes();
    const target = routes.find((n) => n.name === props.tabItem.name);
    if (target) {
      const comp = target.components?.default;
      // @ts-ignore
      let __file = comp?.__file as string;
      if (isFunction(comp)) {
        try {
          // @ts-ignore
          const res = await comp();
          __file = res?.default?.__file;
        } catch (error) {
          console.log(error);
        }
      }
      if (__file) {
        const filePath = `/__open-in-editor?file=${__file}`;
        fetch(filePath);
      }
    }
  };

  defineExpose({
    removeTab,
  });
</script>

<template>
  <a-dropdown :trigger="[isExtra ? 'click' : 'contextmenu']">
    <a v-if="isExtra" class="ant-dropdown-link" @click.prevent>
      <down-outlined :style="{ fontSize: '20px' }" />
    </a>
    <template #overlay>
      <a-menu style="user-select: none">
        <a-menu-item key="1" :disabled="activeKey !== tabItem.fullPath" @click="reloadPage">
          <reload-outlined />
          重新加载
        </a-menu-item>
        <a-menu-item key="2" @click="removeTab">
          <close-outlined />
          关闭标签页
        </a-menu-item>
        <a-menu-divider />
        <a-menu-item key="3" @click="closeLeft">
          <vertical-right-outlined />
          关闭左侧标签页
        </a-menu-item>
        <a-menu-item key="4" @click="closeRight">
          <vertical-left-outlined />
          关闭右侧标签页
        </a-menu-item>
        <a-menu-divider />
        <a-menu-item key="5" @click="closeOther">
          <column-width-outlined />
          关闭其它标签页
        </a-menu-item>
        <a-menu-item key="6" @click="closeAll">
          <minus-outlined />
          关闭全部标签页
        </a-menu-item>
      </a-menu>
    </template>
  </a-dropdown>
</template>

<style lang="less" scoped></style>
