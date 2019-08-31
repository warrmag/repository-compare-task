<?php
declare(strict_types=1);

namespace App\DTO;

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
}