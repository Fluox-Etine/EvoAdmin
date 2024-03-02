package handler

import (
	"generator/internal/logic/generator"
	"generator/internal/types"
	"generator/response"
	"github.com/gin-gonic/gin"
)

func GenerateHandler(c *gin.Context) {
	// TODO 后期加上参数校验
	var req types.GenerateType

	if err := c.ShouldBindJSON(&req); err != nil {
		response.Failure(c, err.Error())
		return
	}

	err := generator.GenerateLogic(&req)
	if err != nil {
		response.Failure(c, err.Error())
		return
	}
	response.Success(c, nil, "生成成功")
}
