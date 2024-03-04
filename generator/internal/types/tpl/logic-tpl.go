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
}
`
