package core

import (
	"fmt"
	"generator/core/config"
	"generator/route"
	"github.com/gin-gonic/gin"
	"os"
	"os/signal"
	"syscall"
)

func NewHttp(conf *config.Http) {
	// 创建gin实例
	r := gin.Default()

	// 设置运行模式
	gin.SetMode(conf.Mode)

	// 设置跨域中间件
	r.Use(CORSMiddleware())

	// 设置初始化路由信息
	route.NewRoutes(r)

	// 启动HTTP服务器
	go func() {
		if err := r.Run(":" + conf.Port); err != nil {
			fmt.Println("http服务启动失败:", err)
		}
	}()

	// 监听系统信号
	signalChan := make(chan os.Signal, 1)
	signal.Notify(signalChan, syscall.SIGINT, syscall.SIGTERM)

	// 等待信号
	<-signalChan

	fmt.Println("关闭其他操作等等")

}
