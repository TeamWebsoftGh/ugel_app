<?php

namespace App\Constants;

abstract class ResponseType
{
    const UNAUTHORIZED = 'UNAUTHORIZED';
    const NOTFOUND = 'NOTFOUND';
    const ERROR = 'ERROR';
    const SUCCESS = 'SUCCESS';

    public static $types = [self::UNAUTHORIZED, self::NOTFOUND, self::ERROR, self::SUCCESS];
}
