<?php
declare(strict_types=1);

namespace App\Service\Comparison;

use App\Api\Git\GitClientInterface;
use App\DTO\ComparisonData;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class ComparisonService implements ComparisonServiceInterface
{
    const MINIMAL_COMPARISION_ITEMS = 2;

    /** @var GitClientInterface */
    private $gitClient;

    /**
     * ComparisionService constructor.
     * @param GitClientInterface $gitClient
     */
    public function __construct(GitClientInterface $gitClient)
    {
        $this->gitClient = $gitClient;
    }

    /**
     * {@inheritdoc}
     */
    public function create(ComparisonData $comparisionData): array
    {
        if (count($comparisionData->repositoryList()) < self::MINIMAL_COMPARISION_ITEMS) {
            throw new InvalidArgumentException(
                "You should provide miminum 2 names/URL's",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $repositoryList = [];
        foreach ($comparisionData->repositoryList() as $item) {
            $repositoryList[] = $this->gitClient->fetchRepository($item);
        }
        return $repositoryList;
    }
}