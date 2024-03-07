package tpl

var CONTROLLER = `<?php

{{.Package}}
class {{.ClassTitle}}
{

{{.ListMethod}}

{{.CreateMethod}}

{{.UpdateMethod}}

{{.DetailMethod}}

{{.DeleteMethod}}

}
`

// TemplateController 模版字段
type TemplateController struct {
	Package      string
	ClassTitle   string
	ListMethod   string
	CreateMethod string
	UpdateMethod string
	DetailMethod string
	DeleteMethod string
}
