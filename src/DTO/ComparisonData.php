<?php
declare(strict_types=1);

namespace App\DTO;

class ComparisonData
{
    /**
     * @var array
     */
    private $repositoryList;

    /**
     * ComparisonData constructor.
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