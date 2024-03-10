package main

import (
	"generator/core"
	"generator/core/config"
	"generator/global"
)

func main() {
	conf, err := config.ViperReadConf()
	if err != nil {
		panic(err)
	}

	global.Conf = conf
	// 配置加载成功

	// 开始初始化 http 服务
	core.NewHttp(&conf.Http)
}
