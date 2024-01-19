<?php

use Gsousadev\LaravelProblemDetailExceptions\Enums\ExceptionsFieldsEnum;

return [
    'app_name' => env('PROBLEM_DETAIL_EXCEPTION_APP_NAME', 'APP'),
    'log_throw' => env('PROBLEM_DETAIL_EXCEPTION_GENERATE_LOGS', true),
    'available_fields_list' => [
        ExceptionsFieldsEnum::TYPE,
        ExceptionsFieldsEnum::TITLE,
        ExceptionsFieldsEnum::STATUS,
        ExceptionsFieldsEnum::DETAIL,
        ExceptionsFieldsEnum::INTERNAL_CODE,
        ExceptionsFieldsEnum::MESSAGE,
        ExceptionsFieldsEnum::USER_MESSAGE,
        ExceptionsFieldsEnum::USER_TITLE,
        ExceptionsFieldsEnum::LOCATION,
        ExceptionsFieldsEnum::TRACE_ID,
        ExceptionsFieldsEnum::PREVIOUS_MESSAGE,
        ExceptionsFieldsEnum::PREVIOUS_CODE,
        ExceptionsFieldsEnum::PREVIOUS_TYPE,
        ExceptionsFieldsEnum::PREVIOUS_LOCATION

    ],
    'renderable_fields_list' => [
        ExceptionsFieldsEnum::TITLE,
        ExceptionsFieldsEnum::STATUS,
        ExceptionsFieldsEnum::USER_MESSAGE,
        ExceptionsFieldsEnum::USER_TITLE,
    ],

];
