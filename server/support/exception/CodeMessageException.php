<?php

namespace support\exception;

use Webman\Http\Request;
use Webman\Http\Response;

class CodeMessageException extends BusinessException
{
    public function render(Request $request): ?Response
    {
        return json(['code' => $this->getCode(), 'message' => $this->getMessage()]);
    }
}