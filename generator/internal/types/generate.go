package types

type GenerateType struct {
	// CURD 选择
	CreateAction bool `json:"create_action"` // 是否创建添加方法
	UpdateAction bool `json:"update_action"` // 是否创建更新方法
	DeleteAction bool `json:"delete_action"` // 是否创建删除方法
	ListAction   bool `json:"list_action"`   // 是否创建列表方法
	// 控制器 逻辑层 模型层
	Controller bool `json:"controller"`
	Model      bool `json:"model"`
	Logic      bool `json:"logic"`
	// 基础配置信息
	ClassName  string `json:"class_name"`  // 类名、文件名，格式采用蛇形命名
	TableName  string `json:"table_name"`  // 表名
	PrimaryKey string `json:"primary_key"` // 主键
	Multiapp   string `json:"multiapp"`    // 应用模式
	// 程序附带配置信息
	PathPrefix  string `json:"path_prefix"`  // 路径前缀
	FirstPrefix string `json:"first_prefix"` // 第一个前缀
	LastPrefix  string `json:"last_prefix"`  // 最后一个前缀
}
