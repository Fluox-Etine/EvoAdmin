<?php

namespace app\http\generate\controller;

class TestController
{
    public function index()
    {
        return renderSuccess('helle world');
    }
}