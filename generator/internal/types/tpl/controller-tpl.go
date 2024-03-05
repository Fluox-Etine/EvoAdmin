package tpl

var CONTROLLER = `
<?php
declare (strict_types=1);

{{.Package}}
class {{.ClassTitle}}
{

{{.ListMethod}}

{{.CreateMethod}}

{{.UpdateMethod}}

{{.DetailMethod}}
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
}
