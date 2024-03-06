package model

import "generator/internal/types"

func PackageModel(data *types.GenerateType) (string, error) {
	var modelStr string
	modelStr = "namespace app\\common\\model;\n"
	modelStr += "use support\\Model;\n"

	// 判断是否软删除
	if data.IsSoftDeletes {
		modelStr += "use Illuminate\\Database\\Eloquent\\SoftDeletes;\n"
	}
	modelStr += "\n" +
		"### " + data.ClassText + "模块模型层"

	return modelStr, nil
}
