package logic

import (
	"fmt"
	"generator/internal/types"
)

func CreateLogic(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 添加操作\n"+
			"     * @param array $params\n"+
			"     * @return bool\n"+
			"     * @throws RespBusinessException\n"+
			"     */\n"+
			"    public function create(array $params): bool\n"+
			"    {\n"+
			"        try {\n"+
			"            // TODO 处理数据\n"+
			"            $data = $params;\n"+
			"            return (new %s())->create($data) != false;\n"+
			"        } catch (\\Exception $e) {\n"+
			"            throw new RespBusinessException($e->getMessage());\n"+
			"        }\n"+
			"    }",
		data.ClassTitle+"Model",
	)
	return str, nil
}
