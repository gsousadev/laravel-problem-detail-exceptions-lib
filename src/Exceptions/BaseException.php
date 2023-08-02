<?php

declare(strict_types=1);

namespace Gsousadev\LaravelProblemDetailExceptions\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class BaseException extends Exception implements ProblemDetailExceptionInterface
{
    protected string $instance = self::class;
    private string $logAppName;

    public function __construct(
        protected string $title = 'Unexpected Error',
        protected string $detail = 'Unexpected Error',
        protected string $userTitle = 'Unexpected Error',
        protected string $userMessage = 'Unexpected Error',
        protected int $httpStatus = Response::HTTP_INTERNAL_SERVER_ERROR,
        protected string $internalCode = 'UNEX0000',
        protected ?\Throwable $previous = null
    ) {
        $this->message = $this->title . ' - ' . $this->detail;
        $this->code = $this->httpStatus;
        $this->instance = get_class($this);
        $this->logAppName = config('problem-detail-exception.log_app_name');
        parent::__construct();
    }

    public function toArray(): array
    {
        return [
            'type'                 => $this->instance,
            'title'                => $this->title,
            'status'               => $this->httpStatus ?? $this->code,
            'detail'               => $this->detail,
            'internal_code'        => $this->internalCode,
            'message'              => $this->message,
            'user_message'         => $this->userMessage,
            'user_title'           => $this->userTitle,
            'location'             => $this->file . ':' . $this->line,
            'trace_id'             => data_get(Log::sharedContext(), 'trace_id'),
        ];
    }

    public function render(Request $request): Response
    {
        $renderableKeys = [
            'title',
            'status',
            'detail',
            'internal_code',
            'user_message',
            'user_title',
            'trace_id'
        ];

        $data = array_filter(
            $this->toArray(),
            function ($key) use ($renderableKeys) {
                return in_array($key, $renderableKeys);
            },
            ARRAY_FILTER_USE_KEY
        );


        return new Response($data, $this->httpStatus);
    }

    public function report(): bool
    {
        Log::error(
            '['. $this->logAppName . '][' . $this->internalCode . ']',
            $this->toArray()
        );

        return true;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function getUserTitle(): string
    {
        return $this->userTitle;
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    public function getInternalCode(): string
    {
        return $this->internalCode;
    }
}

