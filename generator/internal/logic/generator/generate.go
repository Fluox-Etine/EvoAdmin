package generator

import (
	"errors"
	"fmt"
	"generator/global"
	"generator/internal/logic/generator/controller"
	"generator/internal/logic/generator/logic"
	"generator/internal/logic/generator/model"
	"generator/internal/types"
	"generator/internal/types/tpl"
	"os"
	"path/filepath"
	"strings"
	"text/template"
	"time"
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
	// 获取当前日期，并格式化为 YYYYMMDD
	date := time.Now().Format("20060102")
	// 获取当前时间戳（纳秒级）
	timestamp := time.Now().UnixNano()
	data.PathOutput = fmt.Sprintf("%s/%s/%d", global.Conf.Generator.PathOutput, date, timestamp)
	controllerStr, err := generateController(data)
	if err != nil {
		return err
	}
	fmt.Println("控制器的字符串", controllerStr)

	logicStr, err := generateLogic(data)
	if err != nil {
		return err
	}
	fmt.Println("逻辑层", logicStr)

	modelStr, err := generateModel(data)
	if err != nil {
		return err
	}
	fmt.Println("模型层", modelStr)
	return nil

}

// 生成控制器
func generateController(data *types.GenerateType) (map[string]string, error) {
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

		detailStr, err := controller.DetailController(data)
		if err != nil {
			return nil, errors.New("生成控制器的detail方法失败:" + err.Error())
		}
		resp["detail"] = detailStr

		// 开始合成生成页面
		templateField := tpl.TemplateController{
			Package:      resp["package"],
			ClassTitle:   data.ClassTitle + "Controller",
			ListMethod:   resp["list"],
			CreateMethod: resp["create"],
			UpdateMethod: resp["update"],
			DetailMethod: resp["detail"],
			DeleteMethod: resp["delete"],
		}

		templateFile, err := template.New("controller_template").Parse(tpl.CONTROLLER)
		if err != nil {
			return nil, errors.New("生成控制器的模板失败:" + err.Error())
		}

		// 开始输出文件
		path := filepath.Join(data.PathOutput, "app", data.Multiapp, "controller")
		if data.PathPrefix != "" {
			path = filepath.Join(data.PathOutput, "app", data.Multiapp, "controller", data.PathPrefix)
		}
		// 判断这个目录是否存在
		if _, err := os.Stat(path); os.IsNotExist(err) {
			// 如果不存在就创建
			err := os.MkdirAll(path, os.ModePerm)
			if err != nil {
				return nil, errors.New("生成控制器的文件夹失败:" + err.Error())
			}
		}
		fileName := filepath.Join(path, data.ClassTitle+"Controller.php")

		file, err := os.Create(fileName)
		if err != nil {
			panic(err)
		}
		defer file.Close()
		err = templateFile.Execute(file, templateField)
		if err != nil {
			return nil, errors.New("生成控制器的文件失败:" + err.Error())
		}
		// 关闭文件
		file.Close()
	}

	return resp, nil
}

// 生成逻辑层
func generateLogic(data *types.GenerateType) (map[string]string, error) {
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

		detailStr, err := logic.DetailLogic(data)
		if err != nil {
			return nil, errors.New("生成逻辑的detail方法失败:" + err.Error())
		}
		resp["detail"] = detailStr

		// 开始合成生成页面
		templateField := tpl.TemplateLogic{
			Package:      resp["package"],
			ClassTitle:   data.ClassTitle + "Logic",
			ListMethod:   resp["list"],
			CreateMethod: resp["create"],
			UpdateMethod: resp["update"],
			DetailMethod: resp["detail"],
			DeleteMethod: resp["delete"],
		}

		templateFile, err := template.New("logic_template").Parse(tpl.CONTROLLER)
		if err != nil {
			return nil, errors.New("生成逻辑层的模板失败:" + err.Error())
		}

		// 开始输出文件
		path := filepath.Join(data.PathOutput, "app", data.Multiapp, "logic")
		if data.PathPrefix != "" {
			path = filepath.Join(data.PathOutput, "app", data.Multiapp, "logic", data.PathPrefix)
		}
		// 判断这个目录是否存在
		if _, err := os.Stat(path); os.IsNotExist(err) {
			// 如果不存在就创建
			err := os.MkdirAll(path, os.ModePerm)
			if err != nil {
				return nil, errors.New("生成逻辑层的文件夹失败:" + err.Error())
			}
		}
		fileName := filepath.Join(path, data.ClassTitle+"Logic.php")

		file, err := os.Create(fileName)
		if err != nil {
			panic(err)
		}
		defer file.Close()
		err = templateFile.Execute(file, templateField)
		if err != nil {
			return nil, errors.New("生成逻辑层的文件失败:" + err.Error())
		}
		// 关闭文件
		file.Close()
	}

	return resp, nil
}

// 生成模型层
func generateModel(data *types.GenerateType) (map[string]string, error) {
	resp := make(map[string]string)

	if data.Model {
		modelStr, err := model.PackageModel(data)
		if err != nil {
			return nil, errors.New("生成model的失败:" + err.Error())
		}
		resp["package"] = modelStr

		// 开始合成生成页面
		templateField := tpl.TemplateModel{
			Package:     resp["package"],
			ClassTitle:  data.ClassTitle + "Model",
			SoftDeletes: "",
			TableName:   data.TableName,
			PrimaryKey:  data.PrimaryKey,
		}

		// 判断是否有软删除
		if data.IsSoftDeletes {
			templateField.SoftDeletes = "\n" +
				"	use SoftDeletes;\n"
		}
		templateFile, err := template.New("logic_template").Parse(tpl.MODEL)
		if err != nil {
			return nil, errors.New("生成逻辑层的模板失败:" + err.Error())
		}

		//path := filepath.Join(data.PathOutput, "common", "model")
		path := filepath.Join(data.PathOutput, "app", "common", "model")
		if data.PathPrefix != "" {
			path = filepath.Join(data.PathOutput, "app", "common", "model", data.PathPrefix)
		}
		// 判断这个目录是否存在
		if _, err := os.Stat(path); os.IsNotExist(err) {
			// 如果不存在就创建
			err := os.MkdirAll(path, os.ModePerm)
			if err != nil {
				return nil, errors.New("生成逻辑层的文件夹失败:" + err.Error())
			}
		}

		fileName := filepath.Join(path, data.ClassTitle+"Model.php")

		file, err := os.Create(fileName)
		if err != nil {
			panic(err)
		}
		defer file.Close()
		err = templateFile.Execute(file, templateField)
		if err != nil {
			return nil, errors.New("生成逻辑层的文件失败:" + err.Error())
		}
		// 关闭文件
		file.Close()
	}
	return resp, nil
}
