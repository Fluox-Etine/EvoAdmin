package tpl

var LOGIC = `
<?php
declare (strict_types=1);

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

type TemplateLogic struct {
	ListMethod   string
	CreateMethod string
	UpdateMethod string
	DetailMethod string
	Package      string
	ClassTitle   string
	DeleteMethod string
}
