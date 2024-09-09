<template>
  <ProConfigProvider>
    <a-watermark :content="userStore.userInfo.username" v-show="userStore.userInfo" :zIndex="9999999"
                 :gap="[75,75]">
      <router-view #="{ Component }">
        <component :is="Component"/>
      </router-view>
    </a-watermark>
  </ProConfigProvider>
</template>

<script setup lang="ts">
import ProConfigProvider from "@/components/core/pro-config-provider/index.vue";
import {useUserStore} from "@/store/modules/user.ts";

const userStore = useUserStore()

</script>
