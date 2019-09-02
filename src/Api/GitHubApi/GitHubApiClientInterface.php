<?php
declare(strict_types=1);

namespace App\Api\GitHubApi;

interface GitHubApiClientInterface
{
    /**
     * @param string $name
     * @return array
     */
    public function fetchRepository(string $name): array ;

    /**
     * @param string $name
     * @return array
     */
    public function fetchPullRequests(string $name): array ;
}
