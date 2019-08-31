<?php
declare(strict_types=1);

namespace App\Service\Comparison;

use App\DTO\ComparisonData;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

interface ComparisonServiceInterface
{
    /**
     * @param ComparisonData $comparisionData
     * @return array
     * @throws InvalidArgumentException
     */
    public function create(ComparisonData $comparisionData): array;
}