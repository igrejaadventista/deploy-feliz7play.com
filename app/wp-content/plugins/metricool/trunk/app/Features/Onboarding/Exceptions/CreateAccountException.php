<?php

namespace Metricool\Features\Onboarding\Exceptions;

class CreateAccountException extends \RuntimeException
{
    public ?string $reason;

    public function __construct(string $message, ?string $reason = null, int $code = 500)
    {
        parent::__construct($message, $code);

        $this->reason = $reason;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }
}
