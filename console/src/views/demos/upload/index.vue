<template>
  <div>
    <Alert message="自定义表单组件示例" type="info" show-icon style="margin-bottom: 12px"/>
    <a-card>
      <SchemaForm @submit="handleSubmit">
        <template #images="{ formModel, field }">
          <SelectImage v-model="formModel[field]" :multiple="formModel['multiple']" :max-num="formModel['max_num']"/>
        </template>
        <template #video="{ formModel, field }">
          <SelectVideo v-model="formModel[field]" :multiple="formModel['multiple']" :max-num="formModel['max_num']"/>
        </template>
        <template #file="{ formModel, field }">
          <SelectFile v-model="formModel[field]" :multiple="formModel['multiple']" :max-num="formModel['max_num']"/>
        </template>
        <template #list="{ formModel }">
          <br>
          <br>
          图片列表：{{ formModel['images'] }}
          <br>
          <br>
          视频资源：{{ formModel['video'] }}
          <br>
          <br>
          文件列表：{{ formModel['file'] }}
        </template>
      </SchemaForm>
    </a-card>
  </div>
</template>
<script lang="tsx" setup>
import {Alert, message} from 'ant-design-vue';
import {type FormSchema, useForm} from '@/components/business/schema-form';

defineOptions({
  name: 'CustomForm',
});

const schemas: FormSchema[] = [
  {
    field: 'multiple',
    component: "Checkbox",
    label: '是否多选',
    colProps: {
      span: 8,
    }
  },
  {
    field: 'max_num',
    component: 'InputNumber',
    label: '上传最大数量',
    defaultValue: 5,
    colProps: {
      span: 8,
    }
  },
  {
    field: 'images',
    slot: 'images',
    label: '图片选择',
    colProps: {
      span: 24,
    }
  },
  {
    field: 'video',
    slot: 'video',
    label: '视频选择',
    colProps: {
      span: 24,
    }
  },
  {
    field: 'file',
    slot: 'file',
    label: '文件选择',
    colProps: {
      span: 24,
    }
  },
  {
    field: 'list',
    slot: 'list',
    label: '已选择资源',
    colProps: {
      span: 24,
    }
  }
];

const [SchemaForm] = useForm({
  labelWidth: 140,
  schemas,
  actionColOptions: {
    span: 24,
  },
});

const handleSubmit = (values: any) => {
  message.success(`click search,values:${JSON.stringify(values)}`);
  console.log('values', values);
};
</script>