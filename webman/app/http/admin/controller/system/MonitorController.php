<?php

namespace app\http\admin\controller\system;

class MonitorController
{

    public function server()
    {
        // 总内存

        return [
            'os' => php_uname('s'),
            'cpu' => cpu_count(),
            'cpu_usage' => sys_getloadavg(),
            'load_average' => sys_getloadavg(),
        ];

    }
}