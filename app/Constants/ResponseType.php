<?php

namespace App\Constants;

abstract class ResponseType
{
    const UNAUTHORIZED = 'UNAUTHORIZED';
    const NOTFOUND = 'NOTFOUND';
    const ERROR = 'error';
    const SUCCESS = 'success';

    public static $types = [self::UNAUTHORIZED, self::NOTFOUND, self::ERROR, self::SUCCESS];
}
