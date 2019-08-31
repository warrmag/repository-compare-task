<?php
declare(strict_types=1);

namespace App\DTO;

use JMS\Serializer\Annotation as Serializer;

class ComparisionData
{
    /**
     * @var array
     */
    private $repositoryList;

    /**
     * ComparisionData constructor.
     * @param array $repositoryList
     */
    public function __construct(array $repositoryList)
    {
        $this->repositoryList = $repositoryList;
    }

    /** @return array */
    public function repositoryList(): array
    {
        return $this->repositoryList;
    }
}