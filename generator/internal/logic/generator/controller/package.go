package controller

import (
	"generator/internal/types"
)

// PackageController 生成控制器的包
func PackageController(data *types.GenerateType) (string, error) {
	var controllerStr string
	if data.PathPrefix == "" {
		controllerStr = "namespace app\\" + data.Multiapp + "\\controller;\n"

		if data.CreateAction || data.ListAction || data.DetailAction || data.UpdateAction || data.DeleteAction {
			controllerStr += "use app\\" + data.Multiapp + "\\logic\\" + data.ClassTitle + "Logic;\n"
		}
	} else {
		controllerStr = "namespace app\\" + data.Multiapp + "\\controller\\" + data.PathPrefix + ";\n"
		if data.CreateAction || data.ListAction || data.DetailAction || data.UpdateAction || data.DeleteAction {
			controllerStr += "use app\\" + data.Multiapp + "\\logic\\" + data.PathPrefix + "\\" + data.ClassTitle + "Logic;\n"
		}
	}

	controllerStr += "use support\\exception\\RespBusinessException;\n" +
		"use support\\Request;\n" +
		"use support\\Response;\n" +
		"\n"
	controllerStr += "### " + data.ClassText + "模块控制器层"

	return controllerStr, nil
}
