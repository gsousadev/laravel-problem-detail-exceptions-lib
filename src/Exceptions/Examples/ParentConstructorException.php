<?php

declare(strict_types=1);

namespace App\Exceptions;

use Gsousadev\LaravelProblemDetailExceptions\Exceptions\ProblemDetailException;

class ParentConstructorException extends ProblemDetailException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct(
            title:        'Titulo curto para erro. Deve ser imutável',
            detail:       'Descrição mais detalhada do erro podendo conter variaveis dinâmicas.' .
                          'Pode ser mutável a cada lançamento dependendo do contexto',
            userTitle:    'Titulo amigavel para usuário final que pode ver o erro',
            userMessage:  'Detalhamento amigavel para usuário que pode ver o erro',
            httpStatus:  500,
            internalCode: 'SHRD0001',
            previous:     $previous
        );
    }

}
