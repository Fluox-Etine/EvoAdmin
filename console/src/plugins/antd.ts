import type {App} from 'vue';
import 'ant-design-vue/dist/reset.css';
import {AButton} from '@/components/core/button/';

export function setupAntd(app: App<Element>) {
    app.component('AButton', AButton);
}
