package main

import (
	"fmt"
	"generator/core"
)

func main() {
	conf, err := core.ViperReadConf()
	if err != nil {
		panic(err)
	}

	// 配置加载成功
	fmt.Println("conf:", conf)

	// 开始初始化 http 服务
	core.NewHttp(&conf.Http)
}
