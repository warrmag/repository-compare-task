<?php
declare(strict_types=1);

namespace App\Api\Git\GitHub;

use App\Api\Git\AbstractGitClient;
use App\Api\Git\GitClientInterface;
use App\DTO\PullRequestData;
use App\DTO\RepositoryData;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GitHubClient extends AbstractGitClient implements GitClientInterface
{
    const GITHUB_URL = 'github.com/';
    const REPOSITORY_PART = 'repos';
    const SEARCH_PART = 'search';
    const ISSUE_PART = 'issues';

    public function __construct(ParameterBagInterface $parameterBag)
    {
        parent::__construct($parameterBag);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRepository(string $item): RepositoryData
    {
        if (filter_var($item, FILTER_VALIDATE_URL)) {
            $item = $this->extractName($item);
        }

        $responseData = $this->getArrayResponse(self::REPOSITORY_PART . '/' . $item);
        if ($responseData === null) {
            return null;
        }
        $responseData['pull_requests'] = $this->fetchPullRequests($item);

        $repository = RepositoryData::fromArray($responseData);

        return $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPullRequests(string $name): PullRequestData
    {
        $options = [
            'query' => [
                'q' => 'repo:' . $name . ' type:pr state:open'
            ]
        ];
        $pullRequests['open'] = $this->getArrayResponse(self::SEARCH_PART . '/' . self::ISSUE_PART, $options);

        $options['query']['q'] = 'repo:' . $name . ' type:pr state:closed';
        $pullRequests['closed'] = $this->getArrayResponse(self::SEARCH_PART . '/' . self::ISSUE_PART, $options);

        $options['query']['q'] = 'repo:' . $name . ' type:pr';
        $pullRequests['total'] = $this->getArrayResponse(self::SEARCH_PART . '/' . self::ISSUE_PART, $options);

        return new PullRequestData(
            $pullRequests['open']['total_count'],
            $pullRequests['closed']['total_count'],
            $pullRequests['total']['total_count']
        );
    }

    /**
     * @param string $url
     * @return string
     */
    private function extractName(string $url): string
    {
        $exploded = explode(self::GITHUB_URL, $url);
        $actionPart = end($exploded);
        $irrelevantData = preg_replace('/([\w]+\/[\w]+)/','', $actionPart);
        $name = str_replace($irrelevantData,'',$actionPart);
        return $name;
    }
}