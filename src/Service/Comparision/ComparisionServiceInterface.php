<?php
declare(strict_types=1);

namespace App\Service\Comparision;

use App\DTO\ComparisionData;
use Symfony\Component\HttpClient\Exception\InvalidArgumentException;

interface ComparisionServiceInterface
{
    /**
     * @param ComparisionData $comparisionData
     * @return array
     * @throws InvalidArgumentException
     */
    public function create(ComparisionData $comparisionData): array;
}