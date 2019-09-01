<?php
declare(strict_types=1);

namespace tests\Api\GitApi;

use App\Api\Git\GitHubClientInterface;
use App\Api\Git\GitHubException;
use App\Api\Git\GitHub\GitHubHubClient;
use App\ValueObject\RepositoryData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class GitHubClientTest extends TestCase
{
    const GITHUB_API = 'https://api.github.com/';
    const EXAMPLE_NAME = 'symfony/symfony-docs';
    /** @var GitHubClientInterface */
    private $gitHubClient;

    /** @var MockObject */
    private $parameterBagMock;

    public function setUp()
    {
        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $this->gitHubClient = new GitHubHubClient($this->parameterBagMock);
    }

    public function testSuccessfulFetchRepository()
    {
        $this->parameterBagMock->method('get')->willReturn(self::GITHUB_API);
        $result = $this->gitHubClient->fetchRepository(self::EXAMPLE_NAME);
        $this->assertInstanceOf(RepositoryData::class, $result);
        $this->assertNotEmpty($result->name());
    }

    public function testFetchFailedByConnection()
    {
        $this->expectException(GitHubException::class);
        $this->parameterBagMock->method('get')->willReturn('http://bad-request');
        $this->gitHubClient->fetchRepository(self::EXAMPLE_NAME);
    }

    public function testRepositoryNotFound()
    {
        $this->expectException(GitHubException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->parameterBagMock->method('get')->willReturn(self::GITHUB_API);
        $this->gitHubClient->fetchRepository(md5(date('YmsHis')));
    }
}