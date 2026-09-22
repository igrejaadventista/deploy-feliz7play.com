<?php

declare(strict_types=1);

namespace Metricool\Exceptions;

class ApiException extends \Exception
{
    protected array $data = [];
    protected int $statusCode = 400;

    public function setResponseCode(int $code): ApiException
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getResponseCode(): int
    {
        return $this->statusCode;
    }

    public function setData(array $data): ApiException
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
