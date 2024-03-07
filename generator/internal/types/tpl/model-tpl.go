package tpl

var MODEL = `<?php

{{.Package}}
class {{.ClassTitle}} extends Model
{
	{{.SoftDeletes}}

	const PrimaryKey = '{{.PrimaryKey}}';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = '{{.TableName}}';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = '{{.PrimaryKey}}';
}
`

type TemplateModel struct {
	Package     string
	ClassTitle  string
	SoftDeletes string
	TableName   string
	PrimaryKey  string
}
