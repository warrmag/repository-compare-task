<?php
declare(strict_types=1);

namespace App\ValueObject;

class CompareRequestData
{
    /**
     * @var array
     */
    private $repositoryList;

    /**
     * @param array $repositoryList
     */
    public function __construct(array $repositoryList)
    {
        $this->repositoryList = $repositoryList;
    }

    /**
     * @return array
     */
    public function getRepositoryList(): array
    {
        return $this->repositoryList;
    }
}
