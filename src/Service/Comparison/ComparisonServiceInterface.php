<?php
declare(strict_types=1);

namespace App\Service\Comparison;

use App\ValueObject\CompareRequestData;
use App\ValueObject\ComparisonData;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

interface ComparisonServiceInterface
{
    /**
     * @param CompareRequestData $compareRequestData
     * @return ComparisonData
     * @throws InvalidArgumentException
     */
    public function create(CompareRequestData $compareRequestData): ComparisonData;

    /**
     * @param CompareRequestData $compareRequestData
     * @return array
     */
    public function findRepositories(CompareRequestData $compareRequestData): array;
}
