package controller

import "generator/internal/types"

// PackageController 生成控制器的包
func PackageController(data *types.GenerateType) (string, error) {
	namespaceStr := "use namespace app\\" + data.Multiapp + "\\controller\\" + data.PathPrefix + ";"
	var LogicStr string
	if data.PathPrefix == "" {
		LogicStr = "use \\" + data.Multiapp + "\\logic\\" + data.LastPrefix + "Logic;"
	} else {
		LogicStr = "use \\" + data.Multiapp + "\\logic\\" + data.PathPrefix + "\\" + data.LastPrefix + "Logic;"
	}

	return namespaceStr + "\n" + LogicStr, nil
}
