---
title: php_generator
language_tabs:
  - shell: Shell
  - http: HTTP
  - javascript: JavaScript
  - ruby: Ruby
  - python: Python
  - php: PHP
  - java: Java
  - go: Go
toc_footers: []
includes: []
search: true
code_clipboard: true
highlight_theme: darkula
headingLevel: 2
generator: "@tarslib/widdershins v4.0.22"

---

# php_generator

Base URLs:

* <a href="http://127.0.0.1:8091">V3版本: http://127.0.0.1:8091</a>

# Authentication

# V3版本

## POST 测试

POST /generate

> Body 请求参数

```json
{
  "create_action": true,
  "update_action": true,
  "delete_action": true,
  "list_action": true,
  "controller": true,
  "model": true,
  "logic": true,
  "class_name": "User_Info",
  "table_name": "user_info",
  "primary_key": "user_id",
  "multiapp": "api",
  "path_prefix": "",
  "class_title": "",
  "is_soft_deletes": true,
  "class_text": "用户信息"
}
```

### 请求参数

|名称|位置|类型|必选|说明|
|---|---|---|---|---|
|body|body|object| 否 |none|

> 返回示例

> 200 Response

```json
{}
```

### 返回结果

|状态码|状态码含义|说明|数据模型|
|---|---|---|---|
|200|[OK](https://tools.ietf.org/html/rfc7231#section-6.3.1)|成功|Inline|

### 返回数据结构

# 数据模型

