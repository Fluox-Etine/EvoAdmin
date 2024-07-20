<?php
namespace app\controller;

use support\Request;
use support\exception\RespBusinessException;

class FooController
{
    public function index(Request $request)
    {
        return response('hello index');
    }
    public function hello(Request $request)
    {
        throw new  RespBusinessException("系统错误",558);
    }
}