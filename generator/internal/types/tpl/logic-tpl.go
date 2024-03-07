package tpl

var LOGIC = `<?php

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
