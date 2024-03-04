package generator

import (
	"errors"
	"fmt"
	"generator/internal/logic/generator/controller"
	"generator/internal/logic/generator/logic"
	"generator/internal/logic/generator/model"
	"generator/internal/types"
	"strings"
)

func GenerateLogic(data *types.GenerateType) error {

	// 处理部分数据
	classNameArray := strings.Split(strings.ToLower(data.ClassName), "_")
	if len(classNameArray) == 1 {
		// 处理前缀 首字母大写
		data.ClassTitle = strings.Title(data.ClassName)
	} else {
		for i := 0; i < len(classNameArray); i++ {
			data.ClassTitle += strings.Title(classNameArray[i])
		}
		// 处理路径前缀只是取第一个
		data.PathPrefix = strings.ToLower(classNameArray[0])
	}

	controllerStr, err := Controller(data)
	if err != nil {
		return err
	}
	fmt.Println("控制器的字符串", controllerStr)

	logicStr, err := Logic(data)
	if err != nil {
		return err
	}
	fmt.Println("逻辑层", logicStr)

	modelStr, err := Model(data)
	if err != nil {
		return err
	}
	fmt.Println("模型层", modelStr)
	return nil

}

func Controller(data *types.GenerateType) (map[string]string, error) {
	resp := make(map[string]string)

	if data.Controller {
		packageStr, err := controller.PackageController(data)
		if err != nil {
			return nil, errors.New("生成控制器的包名失败:" + err.Error())
		}
		resp["package"] = packageStr

		createStr, err := controller.CreateController(data)
		if err != nil {
			return nil, errors.New("生成控制器的create方法失败:" + err.Error())
		}
		resp["create"] = createStr

		listStr, err := controller.ListController(data)
		if err != nil {
			return nil, errors.New("生成控制器的list方法失败:" + err.Error())
		}
		resp["list"] = listStr

		deleteStr, err := controller.DeleteController(data)
		if err != nil {
			return nil, errors.New("生成控制器的delete方法失败:" + err.Error())
		}
		resp["delete"] = deleteStr

		updateStr, err := controller.UpdateController(data)
		if err != nil {
			return nil, errors.New("生成控制器的update方法失败:" + err.Error())
		}
		resp["update"] = updateStr
	}
	return resp, nil
}

func Logic(data *types.GenerateType) (map[string]string, error) {
	resp := make(map[string]string)

	if data.Logic {
		packageStr, err := logic.PackageLogic(data)
		if err != nil {
			return nil, errors.New("生成逻辑的包名失败:" + err.Error())
		}
		resp["package"] = packageStr

		createStr, err := logic.CreateLogic(data)
		if err != nil {
			return nil, errors.New("生成逻辑的create方法失败:" + err.Error())
		}
		resp["create"] = createStr

		listStr, err := logic.ListLogic(data)
		if err != nil {
			return nil, errors.New("生成逻辑的list方法失败:" + err.Error())
		}
		resp["list"] = listStr

		updateStr, err := logic.UpdateLogic(data)
		if err != nil {
			return nil, errors.New("生成逻辑的update方法失败:" + err.Error())
		}
		resp["update"] = updateStr

		deleteStr, err := logic.DeleteLogic(data)
		if err != nil {
			return nil, errors.New("生成逻辑的delete方法失败:" + err.Error())
		}
		resp["delete"] = deleteStr
	}
	return resp, nil
}

func Model(data *types.GenerateType) (map[string]string, error) {
	resp := make(map[string]string)

	if data.Model {
		modelStr, err := model.PackageModel()
		if err != nil {
			return nil, errors.New("生成model的失败:" + err.Error())
		}
		resp["package"] = modelStr
	}
	return resp, nil
}
