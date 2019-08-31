<?php
declare(strict_types=1);

namespace tests\Api\GitApi;

use App\Api\Git\GitClientInterface;
use App\Api\Git\GitException;
use App\Api\Git\GitHub\GitHubClient;
use App\DTO\RepositoryData;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;

class GitHubClientTest extends TestCase
{
    const GITHUB_API = 'https://api.github.com/';
    const EXAMPLE_NAME = 'symfony/symfony-docs';
    /** @var GitClientInterface */
    private $gitHubClient;

    /** @var MockObject */
    private $parameterBagMock;

    public function setUp()
    {
        $this->parameterBagMock = $this->createMock(ParameterBagInterface::class);
        $this->gitHubClient = new GitHubClient($this->parameterBagMock);
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
        $this->expectException(GitException::class);
        $this->parameterBagMock->method('get')->willReturn('http://bad-request');
        $this->gitHubClient->fetchRepository(self::EXAMPLE_NAME);
    }

    public function testRepositoryNotFound()
    {
        $this->expectException(GitException::class);
        $this->expectExceptionCode(Response::HTTP_NOT_FOUND);
        $this->parameterBagMock->method('get')->willReturn(self::GITHUB_API);
        $this->gitHubClient->fetchRepository(md5(date('YmsHis')));
    }
}