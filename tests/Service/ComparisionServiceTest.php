<?php
declare(strict_types=1);

namespace tests\Service\UnitTests;

use App\Api\Git\GitHub\GitHubClient;
use App\DTO\ComparisionData;
use App\DTO\RepositoryData;
use App\Service\Comparision\ComparisionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class ComparisionServiceTest extends TestCase
{
    const EXAMPLE_REPOSITORIES = ["https://api.github.com/symfony/symfony", "lexik/LexikJWTAuthenticationBundle"];

    /** @var MockObject */
    private $gitClientMock;

    /** @var ComparisionService */
    private $comparisionService;

    public function setUp(): void
    {
        $this->gitClientMock = $this->createMock(GitHubClient::class);
        $this->comparisionService = new ComparisionService($this->gitClientMock);
    }

    public function testTooFewArgumentsGiven(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $repositoryList[] = self::EXAMPLE_REPOSITORIES[0];
        $data = new ComparisionData($repositoryList);
        $this->comparisionService->create($data);
    }

    public function testCreateSuccessfulComparision(): void
    {
        $data = new ComparisionData(self::EXAMPLE_REPOSITORIES);
        $this->gitClientMock->method('fetchRepository')->willReturn($this->createMock(RepositoryData::class));
        $result = $this->comparisionService->create($data);
        $this->assertIsArray($result);
        $this->assertInstanceOf(RepositoryData::class, $result[0]);
    }
}