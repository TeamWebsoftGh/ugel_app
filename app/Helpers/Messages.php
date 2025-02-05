<?php

namespace App\Enums;

abstract class Messages {
    public const DELETE_PROMPT = 'Are you sure you want to delete this record?';
    public const REMOVE_PROMPT = 'Are you sure you want to remove this element?';
    public const SAVE_PROMPT = 'Save this record?';
    public const GENERATE_CERT_PROMPT = 'Are you sure you want to issue certificate?';

    public static $types = [self::UNAUTHORIZED, self::NOTFOUND, self::ERROR, self::SUCCESS];
}


