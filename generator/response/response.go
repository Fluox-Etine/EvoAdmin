package response

import (
	"net/http"

	"github.com/gin-gonic/gin"
)

// Success 返回成功的JSON响应
func Success(c *gin.Context, data interface{}, message ...string) {
	status := http.StatusOK
	msg := "success"
	if len(message) > 0 {
		msg = message[0]
	}

	c.JSON(status, gin.H{
		"status":  status,
		"message": msg,
		"data":    data,
	})

	return
}

// Failure 返回失败的JSON响应
func Failure(c *gin.Context, message string, statusCode ...int) {
	status := http.StatusOK
	if len(statusCode) > 0 {
		status = statusCode[0]
	}

	c.JSON(status, gin.H{
		"status":  500,
		"message": message,
	})
	return
}
