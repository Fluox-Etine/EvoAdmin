package logic

import (
	"generator/internal/types"
)

func PackageLogic(data *types.GenerateType) (string, error) {
	var loginStr string
	if data.PathPrefix == "" {
		loginStr = "namespace app\\" + data.Multiapp + "\\logic;\n"
		loginStr += "use app\\common\\model\\" + data.ClassTitle + "Model;\n"
	} else {
		loginStr = "namespace app\\" + data.Multiapp + "\\logic\\" + data.PathPrefix + ";\n"
		loginStr += "use app\\common\\model\\" + data.PathPrefix + "\\" + data.ClassTitle + "Model;\n"
	}
	if data.ListAction {
		loginStr += "use Illuminate\\Contracts\\Pagination\\LengthAwarePaginator;\n"
	}

	loginStr += "use support\\exception\\RespBusinessException;\n" +
		"\n"

	loginStr += "### " + data.ClassText + "模块逻辑层"

	return loginStr, nil
}
