<?php
declare(strict_types=1);

namespace App\Api\GitHubApi;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubApiClient implements GitHubApiClientInterface
{
    const GITHUB_URL = 'github.com/';
    const REPOSITORY_PART = 'repos';
    const SEARCH_PART = 'search';
    const ISSUE_PART = 'issues';

    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient, ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->httpClient = $httpClient;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRepository(string $item): array
    {
        if (filter_var($item, FILTER_VALIDATE_URL)) {
            $item = $this->extractName($item);
        }

        $responseData = $this->getArrayResponse(self::REPOSITORY_PART . '/' . $item);
        if ($responseData === null) {
            return null;
        }
        $responseData = array_merge($responseData, $this->fetchPullRequests($item));

        return $responseData;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchPullRequests(string $name): array
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

        return [
            'open_pull_requests' =>  $pullRequests['open']['total_count'],
            'closed_pull_requests' => $pullRequests['closed']['total_count'],
            'total_pull_requests' => $pullRequests['total']['total_count']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getArrayResponse(string $urlPart, array $options = []): array
    {
        try {
            $response = $this->httpClient->request(
                Request::METHOD_GET,
                $this->parameterBag->get('github_api') . $urlPart,
                $options
            );
            return $response->toArray();
        } catch (ClientExceptionInterface $exception) {
            throw new GitHubApiException($exception->getMessage(), $exception->getCode());
        } catch (ExceptionInterface $exception) {
            throw new GitHubApiException("Whoops, something went wrong", $exception->getCode());
        }
    }

    /**
     * @param string $url
     * @return string
     */
    private function extractName(string $url): string
    {
        $exploded = explode(self::GITHUB_URL, $url);
        $actionPart = end($exploded);
        $irrelevantData = preg_replace('/([\w]+\/[\w]+)/', '', $actionPart);
        $name = str_replace($irrelevantData, '', $actionPart);
        return $name;
    }
}
