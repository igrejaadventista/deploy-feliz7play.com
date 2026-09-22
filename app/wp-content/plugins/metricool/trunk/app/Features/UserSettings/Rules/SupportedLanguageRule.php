<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings\Rules;

use Metricool\Support\Helpers\Collection;
use Metricool\Support\Helpers\Storages\LanguagesConfig;
use Metricool\Support\Validation\Rules\AbstractRule;

class SupportedLanguageRule extends AbstractRule
{
    private Collection $languages;

    public function __construct(LanguagesConfig $languages, array $parameters = [])
    {
        parent::__construct($parameters);

        $this->languages = new Collection($languages->all());
    }

    /**
     * Checks if the value is a language supported by Metricool
     * @inheritDoc
     */
    public function validate(string $field, $value, array $data): void
    {
        $isValidLanguage = $this->languages->where('value', $value)->count() > 0;
        if (!$isValidLanguage) {
            $this->fail(esc_html(
                sprintf(
                    // translators: %s is the invalid language code submitted by the user.
                    __('%s is not a supported language', 'metricool'),
                    $value
                )
            ));
        }
    }
}
