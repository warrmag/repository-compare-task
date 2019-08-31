<?php
declare(strict_types=1);

namespace App\DTO;

class RepositoryData
{
    /** @var string */
    private $name;

    /** @var string */
    private $stars;

    /** @var int */
    private $openIssues;

    /** @var int */
    private $watchers;

    /** @var string */
    private $language;

    /** @var int */
    private $subscribers;

    /** @var string|null */
    private $description;

    /** @var int */
    private $forks;

    /** @var \DateTime */
    private $lastRelease;

    /** @var PullRequestData */
    private $pullRequest;

    /**
     * @param array $data
     * @return RepositoryData
     * @throws \Exception
     */
    public static function fromArray(array $data)
    {
        return new self(
                $data['name'],
                $data['stargazers_count'],
                $data['open_issues'],
                $data['watchers'],
                $data['subscribers_count'],
                $data['forks'],
                new \DateTime($data['updated_at']),
                $data['language'],
                $data['description'],
                $data['pull_requests']
        );
    }

    /**
     * RepositoryData constructor.
     * @param string $name
     * @param int $stars
     * @param int $openIssues
     * @param int $watchers
     * @param int $subscribers
     * @param int $forks
     * @param \DateTime $lastRelease
     * @param string $language
     * @param string|null $description
     * @param PullRequestData $pullRequest
     */
    public function __construct(
        string $name,
        int $stars,
        int $openIssues,
        int $watchers,
        int $subscribers,
        int $forks,
        \DateTime $lastRelease,
        string $language,
        ?string $description,
        PullRequestData $pullRequest
    ) {
        $this->name = $name;
        $this->stars = $stars;
        $this->openIssues = $openIssues;
        $this->watchers = $watchers;
        $this->subscribers = $subscribers;
        $this->language = $language;
        $this->description = $description;
        $this->forks = $forks;
        $this->lastRelease = $lastRelease;
        $this->pullRequest = $pullRequest;
    }

    /** @return string */
    public function name(): string
    {
        return $this->name;
    }

    /** @return string */
    public function stars(): string
    {
        return $this->stars;
    }

    /** @return int */
    public function openIssues(): int
    {
        return $this->openIssues;
    }

    /** @return int */
    public function watchers(): int
    {
        return $this->watchers;
    }

    /** @return string */
    public function language(): string
    {
        return $this->language;
    }

    /** @return int */
    public function subscribers(): int
    {
        return $this->subscribers;
    }

    /** @return string|null */
    public function description(): ?string
    {
        return $this->description;
    }

    /** @return int */
    public function forks(): int
    {
        return $this->forks;
    }

    /** @return \DateTime */
    public function lastRelease(): \DateTime
    {
        return $this->lastRelease;
    }

    /** @return PullRequestData */
    public function pullRequest(): PullRequestData
    {
        return $this->pullRequest;
    }
}