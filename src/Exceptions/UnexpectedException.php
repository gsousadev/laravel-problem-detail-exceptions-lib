<?php

declare(strict_types=1);

namespace Gsousadev\LaravelProblemDetailExceptions\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UnexpectedException extends ProblemDetailException
{
    public function __construct(
        \Throwable $e
    ) {
        $details = [
            'exception_code'    => $e->getCode(),
            'exception_line'    => $e->getLine(),
            'exception_message' => $e->getMessage(),
            'exception_file'    => $e->getFile()
        ];

        $statusCode = $this->getValidStatusCode($e);

        parent::__construct(
            title       : $statusCode == Response::HTTP_INTERNAL_SERVER_ERROR ? "Erro Inesperado" : $e->getMessage(),
            detail      : json_encode($details),
            userTitle   : 'Ocorreu um erro inesperado',
            userMessage : 'Entre em contato com o administrador do sistema',
            httpStatus  : $statusCode,
            internalCode: "UNEXPECTED_ERROR",
            previous    : $e
        );
    }

    private function getValidStatusCode(\Throwable $e)
    {
        if (method_exists($e, 'getStatusCode') &&
            in_array($e->getStatusCode(), array_keys(Response::$statusTexts))) {
            return $e->getStatusCode();
        }

        return in_array($e->getCode(), array_keys(Response::$statusTexts)) ?
            $e->getCode() :
            Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}
