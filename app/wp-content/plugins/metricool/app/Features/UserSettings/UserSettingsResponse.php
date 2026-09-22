<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings;

use Metricool\Support\Helpers\Collection;
use Metricool\Features\UserSettings\Fields\Field;

class UserSettingsResponse
{
    /**
     * The user settings in a key-value array
     */
    private array $response;

    /**
     * The fields from config/user_settings.php converted to {@see Field}
     * instances grouped in a Collection
     * @var Collection|Field[]
     */
    private Collection $fields;

    /**
     * The section to filter the settings by, useful to return only a subset
     * of settings
     */
    private ?string $section = null;

    public function __construct(Collection $fields)
    {
        $this->fields = $fields;
    }

    /**
     * Return the user settings as a key-value array
     */
    public function get(): array
    {
        return $this->response;
    }

    /**
     * Set the section to filter the settings by
     */
    public function setSection(string $section): void
    {
        $this->section = $section;
    }

    /**
     * Parse the fields and return the user settings response with the values
     * bound to their keys
     */
    public function parse(): self
    {
        if (!empty($this->section)) {
            $this->fields = $this->fields->where('section', $this->section);
        }

        $this->response = $this->getUserSettings();

        return $this;
    }

    /**
     * Bind the field names with their values into a key-value array
     */
    private function getUserSettings(): array
    {
        $values = [];
        foreach ($this->fields as $field) {
            $values[$field->getName()] = $field->getValue();
        }
        return $values;
    }
}
