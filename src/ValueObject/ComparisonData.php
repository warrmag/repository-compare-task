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

    public function addProperty(string $propertyName, ComparedPropertyData $propertyData): void
    {
        $this->comparedPropertyList[$propertyName] = $propertyData;
    }
}