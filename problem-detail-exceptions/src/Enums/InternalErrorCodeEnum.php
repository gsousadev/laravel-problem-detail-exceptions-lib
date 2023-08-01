<?php

declare(strict_types=1);

namespace Gsousadev\LaravelProblemDetailExceptions\Enums;

enum InternalErrorCodeEnum: string
{
    case UNEX0001 = 'Erro sistêmico Inesperado';
    case SHRD0001 = 'Erro shared 1';
    case SHRD0002 = 'Erro shared 2';
}
