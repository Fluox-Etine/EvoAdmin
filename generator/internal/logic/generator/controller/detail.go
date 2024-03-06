package controller

import (
	"fmt"
	"generator/internal/types"
)

func DetailController(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 详情操作\n"+
			"     * @param Request $request\n"+
			"     * @return Response\n"+
			"     * @throws RespBusinessException\n"+
			"     */\n"+
			"    public function detail(Request $request): Response\n"+
			"    {\n"+
			"        if (new %s())->detail($request->get())) {\n"+
			"            return renderSuccess(compact('detail'));\n"+
			"        }\n"+
			"        return renderError('获取失败');\n "+
			"   }",
		data.ClassTitle+"Logic",
	)
	return str, nil
}
