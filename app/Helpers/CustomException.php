<?php

use Illuminate\Http\Response;
use Illuminate\Support\Str;

function handleApiExceptions($request, Throwable $exception): Response
{
    $replacements = prepareApiReplacements($exception);

    $response = config('api.error_format', [
        'message' => ':message',
        'status_code' => ':status_code',
    ]);

    array_walk_recursive($response, function (&$value) use ($replacements) {
        if (Str::startsWith($value, ':') && isset($replacements[$value])) {
            $value = $replacements[$value];
        }
    });

    $response = recursivelyRemoveEmptyApiReplacements($response);

    return new Response($response, getStatusCode($exception), getHeaders($exception));
}

function prepareApiReplacements(Throwable $exception): array
{
    $code = getStatusCode($exception);

    $message = $exception->getMessage() ?: sprintf('%d %s', $code, Response::$statusTexts[$code]);

    $replacements = [
        ':message' => $message,
        ':status_code' => $code,
    ];

    if ($exception instanceof \Illuminate\Validation\ValidationException) {
        $replacements[':errors'] = $exception->errors();
        $replacements[':status_code'] = $exception->status;
    }

    if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
        $replacements[':status_code'] = "401";
    }

    if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
        $replacements[':status_code'] = "204";
        $replacements[':message'] = "No result for item.";
    }

    if ($code = $exception->getCode()) {
        $replacements[':code'] = $code;
    }

    if (config('api.debug')) {
        $replacements[':debug'] = [
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'class' => get_class($exception),
            'trace' => explode("\n", $exception->getTraceAsString()),
        ];

        if (!is_null($exception->getPrevious())) {
            $currentTrace = $replacements[':debug']['trace'];

            $replacements[':debug']['trace'] = [
                'previous' => explode("\n", $exception->getPrevious()->getTraceAsString()),
                'current' => $currentTrace,
            ];
        }
    }

    return $replacements;
}

function recursivelyRemoveEmptyApiReplacements(array $input): array
{
    foreach ($input as &$value) {
        if (is_array($value)) {
            $value = recursivelyRemoveEmptyApiReplacements($value);
        }
    }

    return array_filter($input, function ($value) {
        if (is_string($value)) {
            return !Str::startsWith($value, ':');
        }

        return true;
    });
}

function getStatusCode(Throwable $exception): int
{
    if ($exception instanceof \Illuminate\Validation\ValidationException) {
        return $exception->status;
    }

    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
        return $exception->getStatusCode();
    }

    if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
        return 400;
    }

    if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
        return 403;
    }

    return 500;
}

function getHeaders(Throwable $exception): array
{
    return ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface)
        ? $exception->getHeaders()
        : [];
}
