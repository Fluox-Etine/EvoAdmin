package controller

import (
	"fmt"
	"generator/internal/types"
)

func DeleteController(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 删除操作\n"+
			"     * @param Request $request\n"+
			"     * @return Response\n"+
			"     * @throws RespBusinessException\n"+
			"     */\n"+
			"    public function delete(Request $request): Response\n"+
			"    {\n"+
			"        if (new %s())->setDelete($request->post())) {\n"+
			"            return renderSuccess('删除成功');\n"+
			"        }\n"+
			"        return renderError('删除失败');\n "+
			"   }",
		data.ClassTitle+"Logic",
	)
	return str, nil
}
