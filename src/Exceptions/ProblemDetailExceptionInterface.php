<?php

namespace Gsousadev\LaravelProblemDetailExceptions\Exceptions;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface ProblemDetailExceptionInterface extends \Throwable
{
    public function __construct(
        string $title,
        string $detail,
        string $userTitle,
        string $userMessage,
        int $httpStatus,
        string $internalCode,
        ?\Throwable $previous
    );

    public function getTitle(): string;

    public function getDetail(): string;

    public function getUserTitle(): string;

    public function getUserMessage(): string;

    public function getHttpStatus(): int;

    public function getInternalCode(): string;

    public function toArray(): array;

    public function render(Request $request): Response;

    public function report(): bool;
}