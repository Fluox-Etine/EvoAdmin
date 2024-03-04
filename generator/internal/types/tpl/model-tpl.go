package tpl

var MODEL = `
<?php
declare (strict_types=1);
{{.Package}}
class {{.ClassTitle}} extends Base
{
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
