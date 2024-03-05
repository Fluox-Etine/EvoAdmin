package controller

import (
	"fmt"
	"generator/internal/types"
)

func UpdateController(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 更新操作\n"+
			"     * @param Request $request\n"+
			"     * @return Response\n"+
			"     * @throws RespBusinessException\n"+
			"     */\n"+
			"    public function update(Request $request): Response\n"+
			"    {\n"+
			"        if (new %s())->update($request->post())) {\n"+
			"            return renderSuccess('更新成功');\n"+
			"        }\n"+
			"        return renderError('更新失败');\n "+
			"   }",
		data.ClassTitle+"Logic",
	)
	return str, nil
}
