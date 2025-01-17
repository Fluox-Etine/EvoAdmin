# 项目介绍

<p align="center">
    <img src="https://ntgo.cn/website/logo.svg" width="120" />
</p>
<p align="center">
    <a  target="_blank">官网（后期完善）</a> |
    <a  target="_blank">文档（后期完善）</a> |  
    <a href="https://gen.ntgo.cn" target="_blank"> 演示</a> 
</p>

<p align="center">
    <img src="https://gitee.com/old-friends-come-again/Evo-PHP-Admin/badge/star.svg?theme=dark" />
    <img src="https://gitee.com/old-friends-come-again/Evo-PHP-Admin/badge/fork.svg?theme=dark" />
    <img src="https://svg.hamm.cn/badge.svg?key=License&value=Apache-2.0&color=da4a00" />
</p>

## 项目介绍

开发初衷是了平常开始能有更多的摸鱼时间，通过简简单单的配置、一键生成后台系统，实现快速开发；通过生成75%，剩下25%就要自己定制化的功能。

后台系统基于 Webman 框架开发。webman是一款基于workerman开发的高性能HTTP服务框架。

前端使用Vue3 + Vite4 + Pinia + Antdv。

如果觉着还不错的话，就请点个 ⭐star 支持一下吧，这将是对我最大的支持和鼓励！

在使用 EvoAdmin 前请认真阅读开源协议。

## 仓库地址

- [Github EvoAdmin](https://github.com/Fluox-Etine/EvoAdmin)
- [Gitee EvoAdmin](https://gitee.com/old-friends-come-again/EvoAdmin)

## 官方交流群

> 用于交流学习，暂时没有（没人气，不玩单机孤儿）

## 计划清单

- [x] 后端代码生成(待反馈)
- [x] 后台管理基础框架(高温锻造)
- [x] 前段代码生成
- [ ] 前端基础组建(高温锻造)
- [ ] 表单设计器(不是json)
- [ ] 慢SQL拦截，做语句分析给优化建议
- [ ] 常用后端组件示例代码；例如：微信和支付宝支付等等（只对接官方SDK；减少composer依赖）

## 环境需求

- 请大爷们看下webman官方文档(注意：禁用函数）。
- PHP >= 8.1
- PHP 扩展没有什么特殊要求，webman能运行即可
- Mysql >= 8.0
- Redis >= 6.0
- node >=18

## 下载项目

- git 拉代码，命令行执行 `composer install`

```shell
git clone https://github.com/Fluox-Etine/EvoAdmin.git
```

```shell
git clone https://gitee.com/old-friends-come-again/EvoAdmin.git
```

## 项目安装

- 把SQL文件导入到数据库中(SQL文件在 data/sql 中请食用最新的sql文件)


- 后端代码

```shell
cd server && composer install 

如果composer 安装失败 请尝试使用国内镜像站点

composer config repo.packagist composer https://mirrors.aliyun.com/composer/
```

- 前端代码

```shell
pnpm install

pnpm dev

pnpm build
```

## 体验地址

[体验地址](https://gen.ntgo.cn)

- 账号：admin
- 密码：123456

> 请勿添加脏数据

## 鸣谢

> 以下排名不分先后

[Webman webman高性能PHP框架](https://www.workerman.net/)

[vue3-antdv-admin 无私奉献大佬基础模板](https://github.com/buqiyuan/vue3-antdv-admin)

[Antdv 腌汁最好看的UI框架](https://www.antdv.com/)

[Vue](https://vuejs.org/)

[Vite](https://vitejs.cn/)

## 演示图片 还在高温锻造中。。。囊中羞涩只能展示几个图

<img src="https://gen.ntgo.cn/github/1.png"/>
<img src="https://gen.ntgo.cn/github/2.png"/>
<img src="https://gen.ntgo.cn/github/3.png"/>
<img src="https://gen.ntgo.cn/github/4.png"/>
<img src="https://gen.ntgo.cn/github/5.png"/>
<img src="https://gen.ntgo.cn/github/6.png"/>
<img src="https://gen.ntgo.cn/github/7.png"/>
<img src="https://gen.ntgo.cn/github/8.png"/>
<img src="https://gen.ntgo.cn/github/9.png"/>

### git提交规范
- `feat` 增加新功能
- `fix` 修复问题/BUG
- `style` 代码风格相关无影响运行结果的
- `perf` 优化/性能提升
- `refactor` 重构
- `revert` 撤销修改
- `test` 测试相关
- `docs` 文档/注释
- `chore` 依赖更新/脚手架配置修改等
- `workflow` 工作流改进
- `ci` 持续集成
- `types` 类型定义文件更改
- `wip` 开发中

#### 鄙人联系方式（请注明来意）
<img src="https://gen.ntgo.cn/github/10.jpeg" style="width: 200px;"/>

