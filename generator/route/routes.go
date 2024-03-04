package route

import (
	"generator/internal/handler"
	"github.com/gin-gonic/gin"
)

func NewRoutes(r *gin.Engine) {
	// 代码生成器
	r.POST("/generate", handler.GenerateHandler)
}
