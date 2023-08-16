<?php

declare(strict_types=1);

namespace Gsousadev\LaravelProblemDetailExceptions\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ClientException extends ProblemDetailException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct(
            title:        'Erro de cliente na requisição',
            detail:       'O servidor não foi capaz de processar a requisição por causa de algo que ' .
                          'é percebido como um erro do cliente',
            userTitle:    'Erro na requisição',
            userMessage:  'Não foi possível processar a solicitação. Tente novamente. Se o problema ' .
                          'persistir, entre em contato com o suporte.',
            httpStatus:   Response::HTTP_BAD_REQUEST,
            internalCode: 'CLIE0001',
            previous:     $previous
        );
    }
}