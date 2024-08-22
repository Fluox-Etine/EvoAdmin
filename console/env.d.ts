/// <reference types="vite/client" />

interface ImportMetaEnv {
    /** 网站标题 */
    readonly VITE_APP_TITLE: string;
    /** API 接口路径 */
    readonly VITE_BASE_API_URL: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
