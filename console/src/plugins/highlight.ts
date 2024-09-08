import hljsVuePlugin from '@highlightjs/vue-plugin';
import hljs from 'highlight.js/lib/core';
import php from 'highlight.js/lib/languages/php';
import 'highlight.js/styles/a11y-dark.css'
import type {App} from "vue";

hljs.registerLanguage('php', php)

export function setupHighlight(app: App<Element>) {
    app.use(hljsVuePlugin);
}