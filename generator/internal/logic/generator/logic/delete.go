package logic

import (
	"fmt"
	"generator/internal/types"
)

func DeleteLogic(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 删除操作\n"+
			"     * @param array $params\n"+
			"     * @return mixed\n"+
			"     */\n"+
			"    public function getDetail(array $params): mixed\n"+
			"    {\n"+
			"        return %s::where([%s::primaryKey => $params['id']])->delete();\n"+
			"    }",
		data.ClassTitle+"Model",
		data.ClassTitle+"Model",
	)

	return str, nil
}
