/// <reference types="vite/client" />

interface ImportMetaEnv {
    /** API 接口路径 */
    readonly VITE_BASE_API_URL: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
