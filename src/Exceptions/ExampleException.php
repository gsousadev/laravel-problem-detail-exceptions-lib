<?php

declare(strict_types=1);

namespace App\Exceptions;

use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ProblemDetailException;
use Symfony\Component\HttpFoundation\Response;

class ExampleException extends ProblemDetailException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct(
            title:        'Titulo curto para erro. Deve ser imutável',
            detail:       'Descrição mais detalhada do erro podendo conter variaveis dinâmicas.' .
                          'Pode ser mutável a cada lançamento dependendo do contexto',
            userTitle:    'Titulo amigavel para usuário final que pode ver o erro',
            userMessage:  'Detalhamento amigavel para usuário que pode ver o erro',
            httpStatus:   Response::HTTP_BAD_REQUEST,
            internalCode: 'SHRD0001',
            previous:     $previous
        );
    }

}
