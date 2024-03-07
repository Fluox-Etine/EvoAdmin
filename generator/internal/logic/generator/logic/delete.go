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
			"     * @return bool\n"+
			"     */\n"+
			"    public function setDelete(array $params): bool\n"+
			"    {\n"+
			"        return %s::where([%s::PrimaryKey => $params['id']])->delete() != false;\n"+
			"    }",
		data.ClassTitle+"Model",
		data.ClassTitle+"Model",
	)

	return str, nil
}
