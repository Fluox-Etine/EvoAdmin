<?php

namespace app\http\generate\controller;

use support\Db;
use support\Response;

class TestController
{
    public function test(): Response
    {
        $randId = rand(110000, 659011503);
        $detail = Db::table('region')->where('adcode', $randId)->first();
        return renderSuccess(compact('detail'));
    }
}