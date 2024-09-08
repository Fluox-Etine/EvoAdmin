/// <reference types="vite/client" />

interface ImportMetaEnv {
    /** 网站标题 */
    readonly VITE_APP_TITLE: string;
    /** 网站部署的目录 */
    readonly VITE_BASE_URL: string;
    /** API 接口路径 */
    readonly VITE_BASE_API_URL: string;
    /** 切片上传分片大小 */
    readonly VITE_CHUNK_SIZE: number;
    /** 静态资源路径 */
    readonly VITE_DOMAIN_URL: string;
    /** 上传的图片类型 */
    readonly VITE_IMAGE_TYPE: string;
    /** 上传的视频类型 */
    readonly VITE_VIDEO_TYPE: string;
    /** 上传的文件类型 */
    readonly VITE_FILE_TYPE: string;
    /** 高德地图的key */
    readonly VITE_AMAP_KEY: string;
    /** 高德地图安全密钥 */
    readonly VITE_AMAP_SECRET_KEY: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}
