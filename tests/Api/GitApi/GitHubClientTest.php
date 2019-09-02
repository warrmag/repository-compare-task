<?php
declare(strict_types=1);

namespace tests\Api\GitApi;

use App\Api\GitHubApi\GitHubApiClient;
use App\Api\GitHubApi\GitHubApiClientInterface;
use App\Api\GitHubApi\GitHubApiException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;

class GitHubClientTest extends TestCase
{
    const GITHUB_API = 'https://api.github.com/';
    const EXAMPLE_NAME = 'symfony/symfony-docs';

    /**
     * @var GitHubApiClientInterface
     */
    private $gitHubClient;

    /**
     * @var MockObject
     */
    private $parameterBagMock;

    public function setUp()
    {
        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $this->gitHubClient = new GitHubApiClient(HttpClient::create(), $this->parameterBagMock);
    }

    public function testSuccessfulFetchRepository()
    {
        $this->parameterBagMock->method('get')->willReturn(self::GITHUB_API);
        $result = $this->gitHubClient->fetchRepository(self::EXAMPLE_NAME);
        $this->assertIsArray($result, 'D you have internet connection?');
        $this->assertNotEmpty($result);
    }

    public function testFetchFailedByConnection()
    {
        $this->expectException(GitHubApiException::class);
        $this->parameterBagMock->method('get')->willReturn('http://bad-request');
        $this->gitHubClient->fetchRepository(self::EXAMPLE_NAME);
    }

    public function testRepositoryNotFound()
    {
        $this->expectException(GitHubApiException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->parameterBagMock->method('get')->willReturn(self::GITHUB_API);
        $this->gitHubClient->fetchRepository(md5(date('YmsHis')));
    }
}
