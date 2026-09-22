<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Rules;

use Metricool\Support\Validation\Rules\AbstractRule;

class FirstDayOfWeekRule extends AbstractRule
{
    /**
     * Checks if the value is a valid first day of the week (1 or 7)
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        if ($value !== '1' && $value !== '7') {
            $this->fail(esc_html__('Please enter a valid day of the week (1 or 7)', 'metricool'));
        }
    }
}
