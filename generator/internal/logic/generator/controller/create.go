package controller

import (
	"fmt"
	"generator/internal/types"
)

func CreateController(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 添加操作\n"+
			"     * @param Request $request\n"+
			"     * @return Response\n"+
			"     * @throws RespBusinessException\n"+
			"     */\n"+
			"    public function create(Request $request): Response\n"+
			"    {\n"+
			"        if (new %s())->create($request->post())) {\n"+
			"            return renderSuccess('添加成功');\n"+
			"        }\n"+
			"        return renderError('添加失败');\n "+
			"   }",
		data.ClassTitle+"Logic",
	)
	return str, nil
}
