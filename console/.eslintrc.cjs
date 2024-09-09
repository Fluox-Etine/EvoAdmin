/* eslint-env node */
require('@rushstack/eslint-patch/modern-module-resolution')

module.exports = {
    root: true,
    'extends': [
        'plugin:vue/vue3-essential',
        'eslint:recommended',
        '@vue/eslint-config-typescript',
    ],
    rules: {
        // ...其他规则
        "vue/multi-word-component-names": ["error", {
            "ignores": ["index"] // 忽略名为 'index' 的组件
        }]
    },
    parserOptions: {
        ecmaVersion: 'latest'
    }
}
