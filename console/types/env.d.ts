/// <reference types="vite/client" />

interface ImportMetaEnv {
    /** 网站标题 */
    readonly VITE_APP_TITLE: string;
    /** 网站部署的目录 */
    readonly VITE_BASE_URL: string;
    /** API 接口路径 */
    readonly VITE_BASE_API_URL: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
