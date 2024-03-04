package controller

import (
	"fmt"
	"generator/internal/types"
)

func ListController(data *types.GenerateType) (string, error) {
	str := fmt.Sprintf(
		"    /**\n"+
			"     * 列表操作\n"+
			"     * @param Request $request\n"+
			"     * @return Response\n"+
			"     * @throws RespBusinessException\n"+
			"     */\n"+
			"    public function list(Request $request): Response\n"+
			"    {\n"+
			"        $list = (new %s())->list($request->get());\n"+
			"        return renderSuccess(compact('list'));\n"+
			"    }",
		data.ClassTitle+"Logic",
	)
	return str, nil
}
