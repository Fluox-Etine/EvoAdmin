package logic

import (
	"generator/internal/types"
)

func PackageLogic(data *types.GenerateType) (string, error) {
	var loginStr string
	if data.PathPrefix == "" {
		loginStr = "namespace app\\" + data.Multiapp + "\\controller;\n"
		loginStr += "use app\\common\\model\\" + data.ClassTitle + "Model;\n"
	} else {
		loginStr = "namespace app\\" + data.Multiapp + "\\controller\\" + data.PathPrefix + ";\n"
		loginStr += "use app\\common\\model\\" + data.PathPrefix + "\\" + data.ClassTitle + "Model;\n"
	}

	loginStr += "use support\\exception\\RespBusinessException;\n" +
		"\n"

	loginStr += "### " + data.ClassText + "模块逻辑层"

	return loginStr, nil
}
