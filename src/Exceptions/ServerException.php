<?php

declare(strict_types=1);

namespace Gsousadev\LaravelProblemDetailExceptions\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ServerException extends ProblemDetailException
{
    public function __construct(?\Throwable $previous = null)
    {
        parent::__construct(
            title:        'Erro interno do servidor',
            detail:       'O servidor encontrou um erro interno e não foi capaz de completar sua requisição',
            userTitle:    'Erro interno do servidor',
            userMessage:  'O servidor encontrou um erro interno e não foi capaz de completar sua requisição.',
            httpStatus:   Response::HTTP_INTERNAL_SERVER_ERROR,
            internalCode: 'UNEX0001',
            previous:     $previous
        );
    }
}