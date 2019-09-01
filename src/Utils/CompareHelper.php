<?php

namespace App\Utils;

use App\ValueObject\ComparedPropertyData;

class CompareHelper implements HelperInterface
{
    /**
     * @param string $name
     * @param array $propertyA
     * @param array $propertyB
     * @return ComparedPropertyData
     * @throws \Exception
     */
    public function compareProperty(string $name, array $propertyA, array $propertyB): ComparedPropertyData
    {
        $advantage = null;

        if (is_int($propertyA['value'])) {
            $advantage = $this->compareIntegers($propertyA, $propertyB);
        } elseif ((bool)strtotime($propertyA['value'])) {
            $advantage = $this->compareDates($propertyA, $propertyB);
            return new ComparedPropertyData(
                $advantage['name'] . ' has fresh update with date: ' . $name,
                (string)$advantage['value'],
                $advantage['name']
            );
        } elseif (is_string($propertyA['value'])) {
            return new ComparedPropertyData(
                $propertyA['value'] . ' versus ' . $propertyB['value'],
                null,
                null
            );
        }

        return new ComparedPropertyData(
            $advantage['name'] . ' has bigger value of ' . $name,
            (string)$advantage['value'],
            $advantage['name']
        );
    }

    /**
     * @param array $propertyA
     * @param array $propertyB
     * @return array
     * @throws \Exception
     */
    private function compareDates(array $propertyA, array $propertyB): array
    {
        $dateA = new \DateTime($propertyA['value']);
        $dateB = new \DateTime($propertyB['value']);
        if ($dateA > $dateB) {
            return [
                'name' => $propertyA['name'],
                'value' => $propertyA['value']
            ];
        }
        return [
            'name' => $propertyA['name'],
            'value' => $propertyA['value']
        ];
    }

    /**
     * @param array $propertyA
     * @param array $propertyB
     * @return null|array
     * @throws \Exception
     */
    private function compareIntegers(array $propertyA, array $propertyB): ?array
    {
        switch (true) {
            case ($propertyA['value'] > $propertyB['value']):
                return [
                    'name' => $propertyA['name'],
                    'value' => $propertyA['value'] - $propertyB['value']
                ];
                break;
            case ($propertyA['value'] < $propertyB['value']):
                return [
                    'name' => $propertyB['name'],
                    'value' => $propertyB['value'] - $propertyA['value']
                ];
                break;
            default:
                return null;
                break;
        }
    }
}