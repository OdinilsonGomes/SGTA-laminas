<?php

namespace SGTA\V1\Extenction;

use Throwable;

class ExpiredToken extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Token expirado.';
        }

        parent::__construct($message, $code, $previous);
    }

}