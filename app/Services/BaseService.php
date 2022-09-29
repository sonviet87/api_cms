<?php

namespace App\Services;

class BaseService
{
    public function _result($status = true, $message = '', $data = null)
    {
        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ];
    }
}
