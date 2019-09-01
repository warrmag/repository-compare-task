<?php
declare(strict_types=1);

namespace App\ValueObject;

class PullRequestData
{
    /** @var int */
    private $open;

    /** @var int */
    private $closed;

    /** @var int */
    private $total;

    /**
     * PullRequestData constructor.
     * @param int $open
     * @param int $closed
     * @param int $total
     */
    public function __construct(int $open, int $closed, int $total)
    {
        $this->open = $open;
        $this->closed = $closed;
        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getOpenAmount(): int
    {
        return $this->open;
    }

    /**
     * @return int
     */
    public function getClosedAmount(): int
    {
        return $this->closed;
    }

    /**
     * @return int
     */
    public function getTotalAmount(): int
    {
        return $this->total;
    }
}