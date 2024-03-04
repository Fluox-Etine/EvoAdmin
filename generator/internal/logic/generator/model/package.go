package model

import "generator/internal/types"

func PackageModel(data *types.GenerateType) (string, error) {
	var modelStr string
	modelStr = "namespace app\\common\\model;\n"
	modelStr += "use support\\Model;\n" +
		"\n"
	modelStr += data.ClassText + "模块模型层"

	return modelStr, nil
}
