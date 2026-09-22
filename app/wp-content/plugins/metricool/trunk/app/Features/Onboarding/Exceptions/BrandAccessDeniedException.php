<?php

namespace Metricool\Features\Onboarding\Exceptions;

/**
 * Exception thrown when the user does not have access to the specific brand
 */
class BrandAccessDeniedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Blog access denied.', 403);
    }
}
