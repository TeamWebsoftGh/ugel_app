<?php

namespace App\Services\Interfaces;

use Illuminate\Support\Collection;

interface IBaseService
{
    public function print(string $content, $filename = 'download.pdf', $format = 'A4', $output = "I");

    public function printGRA(string $content, $filename = 'download.pdf', $format = 'A4-L', $output = "I");
}
