<?php
declare(strict_types=1);

namespace tests\Service\UnitTests;

use App\Api\GitHubApi\GitHubApiClient;
use App\Utils\CompareHelper;
use App\ValueObject\ComparedPropertyData;
use App\ValueObject\CompareRequestData;
use App\ValueObject\ComparisonData;
use App\Service\Comparison\ComparisonService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

class ComparisonServiceTest extends TestCase
{
    const EXAMPLE_REPOSITORIES = ["https://api.github.com/symfony/symfony", "lexik/LexikJWTAuthenticationBundle"];

    /**
     * @var MockObject
     */
    private $gitClientMock;

    /**
     * @var ComparisonService
     */
    private $comparisonService;

    /**
     * @var MockObject
     */
    private $helper;

    /**
     * @var MockObject
     */
    private $params;

    public function setUp(): void
    {
        $this->gitClientMock = $this->createMock(GitHubApiClient::class);
        $this->params = $this->createMock(ParameterBagInterface::class);
        $this->helper = $this->createMock(CompareHelper::class);
        $this->comparisonService = new ComparisonService(
            $this->gitClientMock,
            $this->params,
            $this->helper
        );
    }

    public function testTooFewArgumentsGiven(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $repositoryList[] = self::EXAMPLE_REPOSITORIES[0];
        $data = new CompareRequestData($repositoryList);
        $this->comparisonService->create($data);
    }

    public function testCreateSuccessfulComparision(): void
    {
        $data = new CompareRequestData(self::EXAMPLE_REPOSITORIES);

        $this->params->expects($this->at(0))->method('get')->willReturn(2);
        $this->params->expects($this->at(1))->method('get')->willReturn(['stars']);
        $this->gitClientMock->method('fetchRepository')->willReturn(['name' => 'test-name', 'stars' => 10]);

        $result = $this->comparisonService->create($data);

        $this->assertInstanceOf(ComparedPropertyData::class, $result->getProperty('stars'));
        $this->assertInstanceOf(ComparisonData::class, $result);
    }
}
