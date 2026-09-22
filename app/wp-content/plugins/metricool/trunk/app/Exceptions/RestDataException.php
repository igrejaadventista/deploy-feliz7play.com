<?php

declare(strict_types=1);

namespace Metricool\Exceptions;

class RestDataException extends \Exception
{
    protected array $data = [];
    protected int $statusCode = 400;

    public function setResponseCode(int $code): RestDataException
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getResponseCode(): int
    {
        return $this->statusCode;
    }

    public function setData(array $data): RestDataException
    {
        $this->data = $data;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
