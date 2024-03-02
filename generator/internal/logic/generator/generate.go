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
		data.FirstPrefix = strings.Title(data.ClassName)
	} else {
		for i := 1; i < len(classNameArray); i++ {
			data.LastPrefix += strings.Title(classNameArray[i])
		}
		// 处理路径前缀只是取第一个
		data.PathPrefix = strings.ToLower(classNameArray[0])
	}

	resp := make(map[string]map[string]string)

	c := &Controller{}
	l := &Logic{}
	m := &Model{}
	generators := []Generator{c, l, m}

	for _, generator := range generators {
		if result, err := generator.Generate(data); err != nil {
			return err // 发生错误时直接返回错误
		} else {
			for k, v := range result {
				resp[k] = map[string]string{"value": v}
			}
		}
	}

	fmt.Println(resp)
	return nil

}

type Generator interface {
	Generate(data *types.GenerateType) (map[string]string, error)
}

type Controller struct{}

func (c *Controller) Generate(data *types.GenerateType) (map[string]string, error) {
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

type Logic struct{}

func (l *Logic) Generate(data *types.GenerateType) (map[string]string, error) {
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

type Model struct{}

func (m *Model) Generate(data *types.GenerateType) (map[string]string, error) {
	resp := make(map[string]string)

	if data.Model {
		modelStr, err := model.BaseModel(data)
		if err != nil {
			return nil, errors.New("生成model的失败:" + err.Error())
		}
		resp["model"] = modelStr
	}
	return resp, nil
}
