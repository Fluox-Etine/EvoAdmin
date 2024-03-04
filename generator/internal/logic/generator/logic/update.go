package logic

import (
	"fmt"
	"generator/internal/types"
)

func UpdateLogic(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 编辑操作\n"+
			"     * @param array $params\n"+
			"     * @return bool\n"+
			"     * @throws RespBusinessException\n"+
			"     */\n"+
			"    public function create(array $params): bool\n"+
			"    {\n"+
			"        try {\n"+
			"            // TODO 处理数据\n"+
			"            $data = $params;\n"+
			"            $where = [%s::primaryKey => $parmas['%s']\n"+
			"            return (new %s())->update($data,$where) != false;\n"+
			"        } catch (\\Exception $e) {\n"+
			"            throw new RespBusinessException($e->getMessage());\n"+
			"        }\n"+
			"    }",
		data.ClassTitle+"Model",
		data.ClassTitle+"Model",
		data.PrimaryKey,
	)

	return str, nil
}
