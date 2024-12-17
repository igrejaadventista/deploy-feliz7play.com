<?php

// Code generated by OpenAPI Generator (https://openapi-generator.tech), manual changes will be lost - read more on https://github.com/algolia/api-clients-automation. DO NOT EDIT.

namespace Algolia\AlgoliaSearch\Model\Search;

use Algolia\AlgoliaSearch\Model\AbstractModel;
use Algolia\AlgoliaSearch\Model\ModelInterface;

/**
 * UserId Class Doc Comment.
 *
 * @category Class
 *
 * @description Unique user ID.
 */
class UserId extends AbstractModel implements ModelInterface, \ArrayAccess, \JsonSerializable
{
    /**
     * Array of property to type mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $modelTypes = [
        'userID' => 'string',
        'clusterName' => 'string',
        'nbRecords' => 'int',
        'dataSize' => 'int',
    ];

    /**
     * Array of property to format mappings. Used for (de)serialization.
     *
     * @var string[]
     */
    protected static $modelFormats = [
        'userID' => null,
        'clusterName' => null,
        'nbRecords' => null,
        'dataSize' => null,
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name.
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'userID' => 'userID',
        'clusterName' => 'clusterName',
        'nbRecords' => 'nbRecords',
        'dataSize' => 'dataSize',
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses).
     *
     * @var string[]
     */
    protected static $setters = [
        'userID' => 'setUserID',
        'clusterName' => 'setClusterName',
        'nbRecords' => 'setNbRecords',
        'dataSize' => 'setDataSize',
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests).
     *
     * @var string[]
     */
    protected static $getters = [
        'userID' => 'getUserID',
        'clusterName' => 'getClusterName',
        'nbRecords' => 'getNbRecords',
        'dataSize' => 'getDataSize',
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
        if (isset($data['userID'])) {
            $this->container['userID'] = $data['userID'];
        }
        if (isset($data['clusterName'])) {
            $this->container['clusterName'] = $data['clusterName'];
        }
        if (isset($data['nbRecords'])) {
            $this->container['nbRecords'] = $data['nbRecords'];
        }
        if (isset($data['dataSize'])) {
            $this->container['dataSize'] = $data['dataSize'];
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

        if (!isset($this->container['userID']) || null === $this->container['userID']) {
            $invalidProperties[] = "'userID' can't be null";
        }
        if (!isset($this->container['clusterName']) || null === $this->container['clusterName']) {
            $invalidProperties[] = "'clusterName' can't be null";
        }
        if (!isset($this->container['nbRecords']) || null === $this->container['nbRecords']) {
            $invalidProperties[] = "'nbRecords' can't be null";
        }
        if (!isset($this->container['dataSize']) || null === $this->container['dataSize']) {
            $invalidProperties[] = "'dataSize' can't be null";
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
     * Gets userID.
     *
     * @return string
     */
    public function getUserID()
    {
        return $this->container['userID'] ?? null;
    }

    /**
     * Sets userID.
     *
     * @param string $userID unique identifier of the user who makes the search request
     *
     * @return self
     */
    public function setUserID($userID)
    {
        $this->container['userID'] = $userID;

        return $this;
    }

    /**
     * Gets clusterName.
     *
     * @return string
     */
    public function getClusterName()
    {
        return $this->container['clusterName'] ?? null;
    }

    /**
     * Sets clusterName.
     *
     * @param string $clusterName cluster to which the user is assigned
     *
     * @return self
     */
    public function setClusterName($clusterName)
    {
        $this->container['clusterName'] = $clusterName;

        return $this;
    }

    /**
     * Gets nbRecords.
     *
     * @return int
     */
    public function getNbRecords()
    {
        return $this->container['nbRecords'] ?? null;
    }

    /**
     * Sets nbRecords.
     *
     * @param int $nbRecords number of records belonging to the user
     *
     * @return self
     */
    public function setNbRecords($nbRecords)
    {
        $this->container['nbRecords'] = $nbRecords;

        return $this;
    }

    /**
     * Gets dataSize.
     *
     * @return int
     */
    public function getDataSize()
    {
        return $this->container['dataSize'] ?? null;
    }

    /**
     * Sets dataSize.
     *
     * @param int $dataSize data size used by the user
     *
     * @return self
     */
    public function setDataSize($dataSize)
    {
        $this->container['dataSize'] = $dataSize;

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