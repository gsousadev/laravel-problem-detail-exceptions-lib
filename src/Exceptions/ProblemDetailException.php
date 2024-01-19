<?php

declare(strict_types=1);

namespace Gsousadev\LaravelProblemDetailExceptions\Exceptions;

use Exception;
use Gsousadev\LaravelProblemDetailExceptions\Enums\ExceptionsFieldsEnum;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

abstract class ProblemDetailException extends Exception
{
    protected string $instance = self::class;
    private string $logAppName;
    private array $renderableFields;
    private array $fields;
    protected ?bool $logThrow = null;

    public function __construct(
        private string $title,
        private string $detail,
        private string $userTitle,
        private string $userMessage,
        private int $httpStatus,
        private string $internalCode,
        private ?\Throwable $previous = null
    ) {
        $this->message = $this->title;
        $this->code = $this->httpStatus;
        $this->instance = get_class($this);
        $this->logAppName = strtoupper(config('problem-detail-exceptions.app_name'));
        $this->validateConfigFields();
        $this->renderableFields = $this->generateRenderableFieldsByConfig();
        $this->fields = $this->generateFieldsByConfig();

        parent::__construct($this->message, $this->code, $this->previous);

        $isDefaultEnableAndLogThrowNull = (is_null($this->logThrow) &&
            config('problem-detail-exceptions.log_throw'));

        if ($isDefaultEnableAndLogThrowNull || $this->logThrow) {
            Log::error(
                '[' . $this->logAppName . '][' . $this->internalCode . ']',
                $this->toArray()
            );
        }
    }

    public function toArray(): array
    {
        $allFields = [
            ExceptionsFieldsEnum::TYPE->value              => $this->instance,
            ExceptionsFieldsEnum::TITLE->value             => $this->title,
            ExceptionsFieldsEnum::STATUS->value            => $this->httpStatus ?? $this->code,
            ExceptionsFieldsEnum::DETAIL->value            => $this->detail,
            ExceptionsFieldsEnum::INTERNAL_CODE->value     => $this->internalCode,
            ExceptionsFieldsEnum::MESSAGE->value           => $this->message,
            ExceptionsFieldsEnum::USER_MESSAGE->value      => $this->userMessage,
            ExceptionsFieldsEnum::USER_TITLE->value        => $this->userTitle,
            ExceptionsFieldsEnum::LOCATION->value          => $this->file . ':' . $this->line,
            ExceptionsFieldsEnum::TRACE_ID->value          => data_get(Log::sharedContext(), 'trace_id'),
            ExceptionsFieldsEnum::PREVIOUS_MESSAGE->value  => $this->previous->getMessage() ?? null,
            ExceptionsFieldsEnum::PREVIOUS_TYPE->value     => $this->previous::class ?? null,
            ExceptionsFieldsEnum::PREVIOUS_CODE->value     => $this->previous->getCode() ?? null,
            ExceptionsFieldsEnum::PREVIOUS_LOCATION->value => $this->previous->file . ':' . $this->previous->line ??
                null,
        ];

        return array_filter(
            $allFields,
            function ($key) {
                return in_array($key, $this->fields);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    public function render(): Response
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
        if (config('problem-detail-exceptions.log_throw')) {
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

    private function validateConfigFields(): void
    {
        $renderableFields = config('problem-detail-exceptions.renderable_fields_list');
        $fields = config('problem-detail-exceptions.available_fields_list');

        if (!is_array($renderableFields) && !is_array($fields)) {
            throw new \InvalidArgumentException(
                'renderable_fields must be an array in config/problem-detail-exceptions.php'
            );
        }

        if (count($renderableFields) === 0 && count($fields) === 0) {
            throw new \InvalidArgumentException(
                'renderable_fields must have at least one element in config/problem-detail-exceptions.php'
            );
        }

        foreach ($renderableFields as $renderableField) {
            if (!($renderableField instanceof ExceptionsFieldsEnum)) {
                throw new \InvalidArgumentException(
                    'renderable_fields must be an array of ExceptionsFieldsEnum in config/problem-detail-exceptions.php'
                );
            }
        }

        foreach ($fields as $field) {
            if (!($field instanceof ExceptionsFieldsEnum)) {
                throw new \InvalidArgumentException(
                    'fields must be an array of ExceptionsFieldsEnum in config/problem-detail-exceptions.php'
                );
            }
        }
    }

    private function generateRenderableFieldsByConfig(): array
    {
        $renderableFields = config('problem-detail-exceptions.renderable_fields_list');

        return array_map(
            function ($renderableField) {
                return $renderableField->value;
            },
            $renderableFields
        );
    }

    private function generateFieldsByConfig()
    {
        $fields = config('problem-detail-exceptions.available_fields_list');

        return array_map(
            function ($field) {
                return $field->value;
            },
            $fields
        );
    }
}
