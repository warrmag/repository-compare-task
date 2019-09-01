<?php
declare(strict_types=1);

namespace App\Service\Comparison;

use App\Api\GitHubApi\GitHubApiClientInterface;
use App\Utils\HelperInterface;
use App\ValueObject\CompareRequestData;
use App\ValueObject\ComparisonData;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class ComparisonService implements ComparisonServiceInterface
{
    /**
     * @var GitHubApiClientInterface
     */
    private $gitClient;

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var HelperInterface
     */
    private $helper;

    /**
     * ComparisionService constructor.
     * @param GitHubApiClientInterface $gitClient
     * @param ParameterBagInterface $params
     */
    public function __construct(
        GitHubApiClientInterface $gitClient,
        ParameterBagInterface $params,
        HelperInterface $helper
    ) {
        $this->gitClient = $gitClient;
        $this->params = $params;
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function create(CompareRequestData $compareRequestData): ComparisonData
    {
        $repositoryList = $this->findRepositories($compareRequestData);

        $comparision = new ComparisonData();
        $properties = $this->params->get('compare_headers');

        foreach ($properties as $name) {
            $comparedProperty = $this->helper->compareProperty(
                $name,
                ['name' => $repositoryList[0]['name'], 'value' => $repositoryList[0][$name]],
                ['name' => $repositoryList[1]['name'], 'value' => $repositoryList[1][$name]]

            );
            $comparision->addProperty($name, $comparedProperty);
        }

        return $comparision;
    }

    /**
     * {@inheritdoc}
     */
    public function findRepositories(CompareRequestData $compareRequestData): array
    {
        $requiredAmount = $this->params->get('required_items_amount');

        if (count($compareRequestData->getRepositoryList()) !== $requiredAmount) {
            throw new InvalidArgumentException(
                "You should provide " . $requiredAmount . " names/URL's",
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $repositoryList = [];
        foreach ($compareRequestData->getRepositoryList() as $item) {
            $repositoryList[] = $this->gitClient->fetchRepository($item);
        }
        return $repositoryList;
    }
}