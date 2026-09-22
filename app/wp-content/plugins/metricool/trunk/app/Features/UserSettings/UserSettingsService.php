<?php

declare(strict_types=1);

namespace Metricool\Features\UserSettings;

use Metricool\Features\UserSettings\Exceptions\StorageSubmitException;
use Metricool\Features\UserSettings\Exceptions\ValidationFailedExceptions;
use Metricool\Features\UserSettings\Exceptions\ValidatorFailedException;
use Metricool\Features\UserSettings\Fields\Field;
use Metricool\Support\Helpers\Collection;

/**
 * This service is responsible for storing and retrieving user settings.
 */
class UserSettingsService
{
    /**
     * The fields from config/user_settings.php converted to {@see Field}
     * instances grouped in a Collection
     * @var Collection|Field[]
     */
    public Collection $fields;

    /**
     * Property is used to keep track of unique field storages when storing
     * settings, so we can submit each storage only once.
     */
    private array $uniqueFieldStorages = [];

    public function __construct(Collection $fields)
    {
        $this->fields = $fields;
    }

    public function getSettings(): Collection
    {
        return $this->fields;
    }

    /**
     * Return an array with keys and values of all the settings, optionally
     * filtered by section.
     * @throws \Exception
     */
    public function getSettingsResponse(?string $section = null): array
    {
        $response = new UserSettingsResponse($this->fields);

        if (!empty($section)) {
            $response->setSection($section);
        }

        return $response->parse()->get();
    }

    /**
     * Validate and update settings and return their updated values. If any
     * field storage is submittable, it will be submitted after all fields are
     * validated.
     *
     * @throws ValidationFailedExceptions with all the validation errors when validation fails
     * @throws StorageSubmitException when it fails to store data to it's storage
     */
    public function storeSettings(array $requestData, \WP_REST_Request $request): Collection
    {
        $validationErrors = [];
        $userSettings = $this->fields->whereIn('name', array_keys($requestData));

        foreach ($userSettings as &$field) {
            $value = $requestData[$field->getName()];

            try {
                $field->setValue($value, $request);
            } catch (ValidatorFailedException $e) {
                $validationErrors[$field->getName()] = $e;
                continue;
            }

            // Store the validated setting for later submission
            $this->storeValidatedSetting($field, $value);
        }

        // After all fields are validated, save the settings through their
        // storages
        $this->saveValidatedSettings();

        // Still throw validation errors if any, this should not block storage
        // submission for valid fields
        if (count($validationErrors) > 0) {
            // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped -- $validationErrors contains ValidatorFailedException objects with pre-escaped messages.
            throw new ValidationFailedExceptions($validationErrors);
        }

        return $userSettings;
    }

    /**
     * Store the validated value into the field's storage and keep track of
     * unique storages to submit later with {@see saveSettings()}
     * @param mixed $value
     */
    private function storeValidatedSetting(Field $field, $value): void
    {
        $field->storage->set($field->getSettingName(), $value);

        // Remember the unique storages to submit later with saveSettings()
        if (empty($this->uniqueFieldStorages[$field->getStorageName()])) {
            $this->uniqueFieldStorages[$field->getStorageName()] = $field->getStorage();
        }
    }

    /**
     * Save all updated unique storages that were mapped in
     * {@see storeValidatedSetting}. The method collects any errors and throws a
     * single exception if any storage submission fails.
     *
     * @throws StorageSubmitException when any storage submission fails - is
     * caught in {@see UserSettingsEndpoint} and the message we return here is
     * only shown to the user has WP_DEBUG set to true.
     */
    private function saveValidatedSettings(): void
    {
        $requestErrors = [];
        foreach ($this->uniqueFieldStorages as $storage) {
            try {
                $storage->save();
            } catch (\Exception $e) {
                $requestErrors[$storage->name] = $e->getMessage();
                continue; // So we can try to submit other storages
            }
        }

        if (count($requestErrors) > 0) {
            $exception = new StorageSubmitException(__('Something went wrong while submitting the settings!', 'metricool'));
            $exception->setErrors($requestErrors);
            throw $exception;
        }
    }
}
