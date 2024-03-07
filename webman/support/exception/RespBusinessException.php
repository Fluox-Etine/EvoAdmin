<?php

namespace support\exception;

use Webman\Http\Request;
use Webman\Http\Response;

class RespBusinessException extends BusinessException
{
    public function render(Request $request): ?Response
    {
        return json(['status' => $this->getCode() ?: 500, 'message' => $this->getMessage()]);
    }
}