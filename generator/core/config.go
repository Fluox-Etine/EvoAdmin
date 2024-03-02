package core

import (
	"github.com/spf13/viper"
)

// Model 系统配置模型
type Model struct {
	Http HTTP `json:"http"`
}

// HTTP 系统配置
type HTTP struct {
	Port string `json:"port"` // 端口 8089  别问为什么是8089字符串 另外一边不想处理
	Mode string `json:"mode"` // 模式 运行模式 debug｜release
}

// ViperReadConf 读取配置文件
func ViperReadConf() (c *Model, err error) {
	c = new(Model)

	v := viper.New()
	v.SetConfigName("conf")
	v.SetConfigType("yaml")
	v.AddConfigPath("./conf")
	if err = v.ReadInConfig(); err != nil {
		panic("读取配置文件失败:" + err.Error())
	}

	if err = v.Unmarshal(c); err != nil {
		panic("序列化配置文件失败:" + err.Error())
	}
	return c, nil
}
