<script setup lang="ts">
import {Drawer, Space, Spin, Image, Descriptions} from 'ant-design-vue';
import {ref} from "vue";
import * as Api from '@/api/backend/uploadFile.ts'
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";
import {formatSizeUnits} from "@/utils";
import {formatToDateTime} from "@/utils/dateUtil.ts";
import type XgPlayer from "@/components/business/xg-player/index.vue";

defineOptions({
  name: 'FilePreviewDrawer',
});
const domain = import.meta.env.VITE_DOMAIN_URL;
const loading = ref(false);
const visible = ref(false);
const record = ref<any>();
const xgPlayer = ref<InstanceType<typeof XgPlayer>>();

const open = async (id: number) => {
  record.value = await Api.detail({id})
  visible.value = true;
  loading.value = false;
}

const getFileTypeInfo = (type) => {
  switch (type) {
    case 10:
      return '图片';
    case 20:
      return '视频';
    case 30:
      return '文件';
  }
};
const handleClose = () => {
  visible.value = false;
}

defineExpose({open});

</script>

<template>
  <div>
    <Drawer title="文件详情" :width="640" :visible="visible" @close="handleClose" destroyOnClose>
      <Spin :spinning="loading" class="preview-drawer-inner-box">
        <Space direction="vertical">
          <template v-if="record.file_type === FileTypeEnum.IMAGE">
            <Image :src='domain + record.file_path'></Image>
          </template>
          <template v-if="record.file_type === FileTypeEnum.VIDEO">
            <XgPlayer
                ref="xgPlayer"
                width="580px"
                height="480px"
                :videoUrl="domain+record.file_path"
                :poster="domain+'/file_icons/video/cover.png'"
                :id="'videoPlayer' + record.id"
            />
          </template>
          <Descriptions bordered :column="1" size="small">
            <Descriptions.Item
                label="文件名称"
                :label-style="{ whiteSpace: 'nowrap' }"
            >
              {{ record.file_name }}
            </Descriptions.Item>
            <Descriptions.Item
                label="文件路径"
                :label-style="{ whiteSpace: 'nowrap' }"
            >
              {{ record.file_path }}
            </Descriptions.Item>
            <Descriptions.Item
                label="文件类型"
                :label-style="{ whiteSpace: 'nowrap' }"
            >
              {{ getFileTypeInfo(record.file_type) }}
            </Descriptions.Item>
            <Descriptions.Item
                label="文件后缀名"
                :label-style="{ whiteSpace: 'nowrap' }"
            >
              {{ record.file_ext }}
            </Descriptions.Item>
            <Descriptions.Item
                label="文件大小"
                :label-style="{ whiteSpace: 'nowrap' }"
            >
              {{ formatSizeUnits(record.file_size) }}
            </Descriptions.Item>
            <Descriptions.Item
                label="文件分组"
                :label-style="{ whiteSpace: 'nowrap' }"
            >
              {{ record.group_name }}
            </Descriptions.Item>
            <Descriptions.Item
                label="上传渠道"
                :label-style="{ whiteSpace: 'nowrap' }"
            >
              {{ record.channel === 10 ? '管理端' : '用户端' }}
            </Descriptions.Item>
            <Descriptions.Item
                label="上传者"
                :label-style="{ whiteSpace: 'nowrap' }"
            >
              {{ record.uploader_name }}
            </Descriptions.Item>
            <Descriptions.Item label="上传时间" :label-style="{ whiteSpace: 'nowrap' }">
              {{ formatToDateTime(record.created_at) }}
            </Descriptions.Item>
            <Descriptions.Item label="下载地址" :label-style="{ whiteSpace: 'nowrap' }">
              <a :href="domain + record.file_path" target="_blank">{{ domain + record.file_path }}</a>
            </Descriptions.Item>
          </Descriptions>
        </Space>
      </Spin>
    </Drawer>
  </div>
</template>

<style scoped lang="less">

</style>