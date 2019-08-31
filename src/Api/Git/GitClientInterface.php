<?php
declare(strict_types=1);

namespace App\Api\Git;

use App\DTO\PullRequestData;
use App\DTO\RepositoryData;

interface GitClientInterface
{
    /**
     * @param string $name
     * @return RepositoryData|null
     */
    public function fetchRepository(string $name): RepositoryData;

    /**
     * @param string $name
     * @return PullRequestData
     */
    public function fetchPullRequests(string $name): PullRequestData;
}