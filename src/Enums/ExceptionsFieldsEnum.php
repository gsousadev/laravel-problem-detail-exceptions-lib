<?php

namespace Gsousadev\LaravelProblemDetailExceptions\Enums;

enum ExceptionsFieldsEnum: string
{
    case TYPE = 'type';
    case TITLE = 'title';
    case STATUS = 'status';
    case DETAIL = 'detail';
    case INTERNAL_CODE = 'internal_code';
    case MESSAGE = 'message';
    case USER_MESSAGE = 'user_message';
    case USER_TITLE = 'user_title';
    case LOCATION = 'location';
    case TRACE_ID = 'trace_id';
}
