<?php
declare(strict_types=1);

namespace App\ValueObject;

/**
 * Serialized by JMS-Serializer on response
 * @package App\ValueObject
 */
class ComparisonData
{
    /**
     * @var array
     */
    private $comparedPropertyList;

    public function __construct()
    {
        $this->comparedPropertyList = [];
    }

    /**
     * @param string $propertyName
     * @param ComparedPropertyData $propertyData
     */
    public function addProperty(string $propertyName, ComparedPropertyData $propertyData): void
    {
        $this->comparedPropertyList[$propertyName] = $propertyData;
    }

    /**
     * @param string $key
     * @return ComparedPropertyData
     * @throws \Exception
     */
    public function getProperty(string $key): ComparedPropertyData
    {
        if (!isset($this->comparedPropertyList[$key])) {
            throw new \Exception('Property is not set');
        }
        return $this->comparedPropertyList[$key];
    }
}
