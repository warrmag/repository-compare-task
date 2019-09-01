<?php
declare(strict_types=1);

namespace App\ValueObject;

/**
 * Serialized by JMS-Serializer on response
 * @package App\ValueObject
 */
class ComparedPropertyData
{
    /**
     * @var null|string
     */
    private $description;

    /**
     * @var string
     */
    private $advantage;

    /**
     * @var null|string
     */
    private $repositoryName;

    public function __construct(string $description, ?string $advantage, ?string $repositoryName)
    {
        $this->description = $description;
        $this->advantage = $advantage;
        $this->repositoryName = $repositoryName;
    }
}