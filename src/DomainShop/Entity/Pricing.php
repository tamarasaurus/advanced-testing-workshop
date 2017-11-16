<?php
declare(strict_types=1);

namespace DomainShop\Entity;

final class Pricing
{
    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $amount;

    /**
     * Pricing constructor.
     * @param string $currency
     * @param int $amount
     * @param string $extension
     */
    public function __construct(string $currency, int $amount, string $extension)
    {
        $this->currency = $currency;
        $this->amount = $amount;
        $this->extension = $extension;
    }

    public function id(): string
    {
        return $this->extension;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
