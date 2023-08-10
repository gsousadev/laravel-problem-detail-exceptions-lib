<?php

declare(strict_types=1);

namespace Gsousadev\LaravelProblemDetailExceptions\Exceptions;

use Exception;
use Gsousadev\LaravelProblemDetailExceptions\Enums\ExceptionsFieldsEnum;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class ProblemDetailException extends Exception implements ProblemDetailExceptionInterface
{
    protected string $instance = self::class;
    private string $logAppName;
    private array $renderableFields;

    public function __construct(
        protected string $title,
        protected string $detail,
        protected string $userTitle,
        protected string $userMessage,
        protected int $httpStatus,
        protected string $internalCode,
        protected ?\Throwable $previous = null
    ) {
        $this->message = $this->title . ' - ' . $this->detail;
        $this->code = $this->httpStatus;
        $this->instance = get_class($this);
        $this->logAppName = strtoupper(config('problem-detail-exceptions.log_app_name'));
        $this->validateRenderableFieldsConfig();
        $this->renderableFields = $this->generateRenderableFiledsByConfig();


        parent::__construct($this->message, $this->code, $this->previous);
    }

    public function toArray(): array
    {
        return [
            ExceptionsFieldsEnum::TYPE->value          => $this->instance,
            ExceptionsFieldsEnum::TITLE->value         => $this->title,
            ExceptionsFieldsEnum::STATUS->value        => $this->httpStatus ?? $this->code,
            ExceptionsFieldsEnum::DETAIL->value        => $this->detail,
            ExceptionsFieldsEnum::INTERNAL_CODE->value => $this->internalCode,
            ExceptionsFieldsEnum::MESSAGE->value       => $this->message,
            ExceptionsFieldsEnum::USER_MESSAGE->value  => $this->userMessage,
            ExceptionsFieldsEnum::USER_TITLE->value    => $this->userTitle,
            ExceptionsFieldsEnum::LOCATION->value      => $this->file . ':' . $this->line,
            ExceptionsFieldsEnum::TRACE_ID->value      => data_get(Log::sharedContext(), 'trace_id'),
        ];
    }

    public function render(Request $request): Response
    {
        $data = array_filter(
            $this->toArray(),
            function ($key) {
                return in_array($key, $this->renderableFields);
            },
            ARRAY_FILTER_USE_KEY
        );


        return new Response($data, $this->httpStatus);
    }

    public function report(): bool
    {
        if (config('problem-detail-exceptions.log_in_exception')) {
            Log::error(
                '[' . $this->logAppName . '][' . $this->internalCode . ']',
                $this->toArray()
            );
        }

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

    private function validateRenderableFieldsConfig(): void
    {
        $renderableFields = config('problem-detail-exceptions.renderable_fields');

        if (!is_array($renderableFields)) {
            throw new \InvalidArgumentException('renderable_fields must be an array');
        }

        if (count($renderableFields) === 0) {
            throw new \InvalidArgumentException('renderable_fields must have at least one element');
        }

        foreach ($renderableFields as $renderableField) {
            if (!($renderableField instanceof ExceptionsFieldsEnum)) {
                throw new \InvalidArgumentException('renderable_fields must be an array of ExceptionsFieldsEnum');
            }
        }
    }

    private function generateRenderableFiledsByConfig(): array
    {
        $renderableFields = config('problem-detail-exceptions.renderable_fields');

        return array_map(
            function ($renderableField) {
                return $renderableField->value;
            },
            $renderableFields
        );
    }
}

