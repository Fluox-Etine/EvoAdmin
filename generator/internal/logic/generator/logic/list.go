package logic

import (
	"fmt"
	"generator/internal/types"
)

func ListLogic(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 设置搜索条件\n"+
			"     * @param array $param\n"+
			"     * @return array\n"+
			"     */\n"+
			"    public function setSearch(array $param): array\n"+
			"    {\n"+
			"        $params = setQueryDefaultValue($param, [\n"+
			"\n        ]);\n"+
			"        $filter = [];\n"+
			"        return $filter;\n"+
			"    }\n"+
			"\n"+
			"    /**\n"+
			"     * 列表操作\n"+
			"     * @param array $params\n"+
			"     * @return Paginator\n"+
			"     * @throws DbException\n"+
			"     */\n"+
			"    public function list(array $params): Paginator\n"+
			"    {\n"+
			"        $where = $this->setSearch($params);\n"+
			"        return (new %s())->where($where)->paginate($params['limit']);\n"+
			"    }",
		data.ClassTitle+"Model",
	)
	return str, nil
}
