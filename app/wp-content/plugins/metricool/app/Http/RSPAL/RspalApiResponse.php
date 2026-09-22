<?php

namespace Metricool\Http\RSPAL;

use Metricool\Vendor\Psr\Http\Message\ResponseInterface;
use Metricool\Exceptions\RestDataException;

class RspalApiResponse
{
    public int $statusCode;
    public object $data;

    public function __construct(int $statusCode, object $data)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public static function fromResponse(ResponseInterface $response): self
    {
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $data = json_decode($body, false);

        if (!is_object($data)) {
            throw new RestDataException('Invalid or empty response from the API');
        }

        if (isset($data->status) && $data->status == 'OK') {
            throw new RestDataException('An error occurred');
        }

        return new self(
            $statusCode,
            $data
        );
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getData(): object
    {
        return $this->data;
    }
}
