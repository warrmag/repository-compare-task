<?php
declare(strict_types=1);

namespace App\DTO;

class PullRequestData
{
    /** @var int */
    private $openPullRequests;

    /** @var int */
    private $totalPullRequests;

    /**
     * PullRequestData constructor.
     * @param int $openPullRequests
     * @param int $totalPullRequests
     */
    public function __construct(int $openPullRequests, int $totalPullRequests)
    {
        $this->openPullRequests = $openPullRequests;
        $this->totalPullRequests = $totalPullRequests;
    }
}