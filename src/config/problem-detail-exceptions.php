<?php

use Gsousadev\LaravelProblemDetailExceptions\Enums\ExceptionsFieldsEnum;

return [
    'app_name' => env('PROBLEM_DETAIL_EXCEPTION_APP_NAME', 'APP'),
    'log_in_exception' => env('PROBLEM_DETAIL_EXCEPTION_GENERATE_LOGS', true),
    'renderable_fields' => [
        ExceptionsFieldsEnum::TYPE,
        ExceptionsFieldsEnum::TITLE,
        ExceptionsFieldsEnum::STATUS,
        ExceptionsFieldsEnum::DETAIL,
        ExceptionsFieldsEnum::INTERNAL_CODE,
        ExceptionsFieldsEnum::MESSAGE,
        ExceptionsFieldsEnum::USER_MESSAGE,
        ExceptionsFieldsEnum::USER_TITLE,
        ExceptionsFieldsEnum::LOCATION,
        ExceptionsFieldsEnum::TRACE_ID
    ]
];