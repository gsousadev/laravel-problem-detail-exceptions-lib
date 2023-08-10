<?php

declare(strict_types=1);

namespace App\Exceptions;

use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ProblemDetailException;
use Symfony\Component\HttpFoundation\Response;

class ExampleException extends ProblemDetailException
{
    protected string $title = 'Titulo curto para erro. Deve ser imutável';
    protected string $detail = 'Descrição mais detalhada do erro podendo conter variaveis dinâmicas.' .
    'Pode ser mutável a cada lançamento dependendo do contexto';
    protected string $userTitle = 'Titulo amigavel para usuário final que pode ver o erro';
    protected string $userMessage = 'Detalhamento amigavel para usuário que pode ver o erro';

    protected int $httpStatus = Response::HTTP_BAD_REQUEST;
    protected string $internalCode = 'SHRD0001';


}
