<?php
declare(strict_types=1);

namespace tests\Service\UnitTests;

use App\Api\Git\GitHub\GitHubClient;
use App\DTO\ComparisonData;
use App\DTO\RepositoryData;
use App\Service\Comparison\ComparisonService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class ComparisonServiceTest extends TestCase
{
    const EXAMPLE_REPOSITORIES = ["https://api.github.com/symfony/symfony", "lexik/LexikJWTAuthenticationBundle"];

    /** @var MockObject */
    private $gitClientMock;

    /** @var ComparisonService */
    private $comparisonService;

    public function setUp(): void
    {
        $this->gitClientMock = $this->createMock(GitHubClient::class);
        $this->comparisonService = new ComparisonService($this->gitClientMock);
    }

    public function testTooFewArgumentsGiven(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $repositoryList[] = self::EXAMPLE_REPOSITORIES[0];
        $data = new ComparisonData($repositoryList);
        $this->comparisonService->create($data);
    }

    public function testCreateSuccessfulComparision(): void
    {
        $data = new ComparisonData(self::EXAMPLE_REPOSITORIES);
        $this->gitClientMock->method('fetchRepository')->willReturn($this->createMock(RepositoryData::class));
        $result = $this->comparisonService->create($data);
        $this->assertIsArray($result);
        $this->assertInstanceOf(RepositoryData::class, $result[0]);
    }
}