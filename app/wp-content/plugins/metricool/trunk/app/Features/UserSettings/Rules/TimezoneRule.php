<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Rules;

use Metricool\Support\Validation\Rules\AbstractRule;

class TimezoneRule extends AbstractRule
{
    /**
     * Checks if the value is a valid timezone identifier
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        $validTimezones = timezone_identifiers_list();

        if (!in_array($value, $validTimezones)) {
            $this->fail(esc_html(
                sprintf(
                    // translators: %s is the invalid timezone submitted by the user.
                    __('%s is not a valid timezone', 'metricool'),
                    $value
                )
            ));
        }
    }
}
