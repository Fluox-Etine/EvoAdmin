<template>
  <div>
    <a-card>
      <div class="border border-br flex flex-col" :style="styles">
        <toolbar
            class="border-b border-br"
            :editor="editorRef"
            :defaultConfig="toolbarConfig"
            :mode="mode"
        />
        <w-editor
            class="flex-1 overflow-hidden"
            v-model="valueHtml"
            :defaultConfig="editorConfig"
            :mode="mode"
            @onCreated="handleCreated"
        />
        <FileModal ref="FilesModal" @handleSubmit="handleSelectSubmit"/>
      </div>
    </a-card>
  </div>
</template>
<script setup lang="ts">
import '@wangeditor/editor/dist/css/style.css' // 引入 css

import type {IEditorConfig, IToolbarConfig} from '@wangeditor/editor'
import {Editor as WEditor, Toolbar} from '@wangeditor/editor-for-vue'
import {computed, type CSSProperties, defineProps, onBeforeUnmount, ref, shallowRef} from 'vue'

import {addUnit} from '@/utils'
import {FileTypeEnum} from "@/enums/fileTypeEnum.ts";

const props = withDefaults(
    defineProps<{
      modelValue?: string
      mode?: 'default' | 'simple'
      height?: string | number
      width?: string | number
    }>(),
    {
      modelValue: '',
      mode: 'default',
      height: '100%',
      width: 'auto',
    }
)

//工具栏配置
const toolbarConfig: Partial<IToolbarConfig> = {
  // 插入哪些菜单
  insertKeys: {
    index: 24, // 自定义插入的位置
    keys: ['uploadAttachment'], // “上传附件”菜单
  },

}
const emit = defineEmits<{
  (event: 'update:modelValue', value: string): void
}>()

// 编辑器实例，必须用 shallowRef
const editorRef = shallowRef()
const FilesModal = ref<any>();
const domain = import.meta.env.VITE_DOMAIN_URL;

let insertFn: any

const editorConfig: Partial<IEditorConfig> = {
  MENU_CONF: {
    uploadImage: {
      customBrowseAndUpload(insert: any) {
        FilesModal.value.openFileModal(FileTypeEnum.IMAGE, true, 10, 0);
        insertFn = insert
      }
    },
    uploadVideo: {
      customBrowseAndUpload(insert: any) {
        FilesModal.value.openFileModal(FileTypeEnum.VIDEO, false, 10, 0);
        insertFn = insert
      }
    },
    uploadAttachment: {
      customBrowseAndUpload(insert: any) {
        FilesModal.value.openFileModal(FileTypeEnum.FILE, false, 10, 0);
        insertFn = insert
      }
    }
  }
}

const styles = computed<CSSProperties>(() => ({
  height: addUnit(props.height),
  width: addUnit(props.width)
}))
const valueHtml = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit('update:modelValue', value)
  }
})


const handleSelectSubmit = (selectItems: string[]) => {
  selectItems.forEach((item) => {
    if (item.file_type === FileTypeEnum.IMAGE) {
      insertFn(domain + item.file_path, item.file_name)
    } else if (item.file_type === FileTypeEnum.FILE) {
      insertFn(item.file_name, domain + item.file_path)
    } else {
      insertFn(domain + item.file_path)
    }
  })
}

// 组件销毁时，也及时销毁编辑器
onBeforeUnmount(() => {
  const editor = editorRef.value
  if (editor == null) return
  editor.destroy()
})

const handleCreated = (editor: any) => {
  editorRef.value = editor // 记录 editor 实例，重要！
}
</script>

<style lang="less">
.w-e-full-screen-container {
  z-index: 999;
}

.w-e-text-container [data-slate-editor] ul {
  list-style: disc;
}

.w-e-text-container [data-slate-editor] ol {
  list-style: decimal;
}

h1 {
  font-size: 2em;
}

h2 {
  font-size: 1.5em;
}

h3 {
  font-size: 1.17em;
}

h4 {
  font-size: 1em;
}

h5 {
  font-size: 0.83em;
}

h1,
h2,
h3,
h4,
h5 {
  font-weight: bold;
}
</style>
