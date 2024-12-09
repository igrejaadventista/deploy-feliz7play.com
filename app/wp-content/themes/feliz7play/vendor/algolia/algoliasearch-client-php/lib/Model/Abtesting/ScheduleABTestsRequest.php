<?php

// Code generated by OpenAPI Generator (https://openapi-generator.tech), manual changes will be lost - read more on https://github.com/algolia/api-clients-automation. DO NOT EDIT.

namespace Algolia\AlgoliaSearch\Model\Abtesting;

use Algolia\AlgoliaSearch\Model\AbstractModel;
use Algolia\AlgoliaSearch\Model\ModelInterface;

/**
 * ScheduleABTestsRequest Class Doc Comment.
 *
 * @category Class
 */
class ScheduleABTestsRequest extends AbstractModel implements ModelInterface, \ArrayAccess, \JsonSerializable
{
    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $modelTypes = [
        'name' => 'string',
        'variants' => '\Algolia\AlgoliaSearch\Model\Abtesting\AddABTestsVariant[]',
        'scheduledAt' => 'string',
        'endAt' => 'string',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $modelFormats = [
        'name' => null,
        'variants' => null,
        'scheduledAt' => null,
        'endAt' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'name' => 'name',
        'variants' => 'variants',
        'scheduledAt' => 'scheduledAt',
        'endAt' => 'endAt',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'name' => 'setName',
        'variants' => 'setVariants',
        'scheduledAt' => 'setScheduledAt',
        'endAt' => 'setEndAt',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'name' => 'getName',
        'variants' => 'getVariants',
        'scheduledAt' => 'getScheduledAt',
        'endAt' => 'getEndAt',
    ];

    /**
     * Associative array for storing property values.
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor.
     *
     * @param mixed[] $data Associated array of property values
     */
    public function __construct(?array $data = null)
    {
        if (isset($data['name'])) {
            $this->container['name'] = $data['name'];
        }
        if (isset($data['variants'])) {
            $this->container['variants'] = $data['variants'];
        }
        if (isset($data['scheduledAt'])) {
            $this->container['scheduledAt'] = $data['scheduledAt'];
        }
        if (isset($data['endAt'])) {
            $this->container['endAt'] = $data['endAt'];
        }
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @return array
     */
    public static function modelTypes()
    {
        return self::$modelTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @return array
     */
    public static function modelFormats()
    {
        return self::$modelFormats;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (!isset($this->container['name']) || null === $this->container['name']) {
            $invalidProperties[] = "'name' can't be null";
        }
        if (!isset($this->container['variants']) || null === $this->container['variants']) {
            $invalidProperties[] = "'variants' can't be null";
        }
        if (!isset($this->container['scheduledAt']) || null === $this->container['scheduledAt']) {
            $invalidProperties[] = "'scheduledAt' can't be null";
        }
        if (!isset($this->container['endAt']) || null === $this->container['endAt']) {
            $invalidProperties[] = "'endAt' can't be null";
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed.
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return 0 === count($this->listInvalidProperties());
    }

    /**
     * Gets name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->container['name'] ?? null;
    }

    /**
     * Sets name.
     *
     * @param string $name A/B test name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets variants.
     *
     * @return \Algolia\AlgoliaSearch\Model\Abtesting\AddABTestsVariant[]
     */
    public function getVariants()
    {
        return $this->container['variants'] ?? null;
    }

    /**
     * Sets variants.
     *
     * @param \Algolia\AlgoliaSearch\Model\Abtesting\AddABTestsVariant[] $variants A/B test variants
     *
     * @return self
     */
    public function setVariants($variants)
    {
        $this->container['variants'] = $variants;

        return $this;
    }

    /**
     * Gets scheduledAt.
     *
     * @return string
     */
    public function getScheduledAt()
    {
        return $this->container['scheduledAt'] ?? null;
    }

    /**
     * Sets scheduledAt.
     *
     * @param string $scheduledAt date and time when the A/B test is scheduled to start, in RFC 3339 format
     *
     * @return self
     */
    public function setScheduledAt($scheduledAt)
    {
        $this->container['scheduledAt'] = $scheduledAt;

        return $this;
    }

    /**
     * Gets endAt.
     *
     * @return string
     */
    public function getEndAt()
    {
        return $this->container['endAt'] ?? null;
    }

    /**
     * Sets endAt.
     *
     * @param string $endAt end date and time of the A/B test, in RFC 3339 format
     *
     * @return self
     */
    public function setEndAt($endAt)
    {
        $this->container['endAt'] = $endAt;

        return $this;
    }

    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param int $offset Offset
     */
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param int $offset Offset
     *
     * @return null|mixed
     */
    public function offsetGet($offset): mixed
    {
        return $this->container[$offset] ?? null;
    }

    /**
     * Sets value based on offset.
     *
     * @param null|int $offset Offset
     * @param mixed    $value  Value to be set
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param int $offset Offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->container[$offset]);
    }
}
