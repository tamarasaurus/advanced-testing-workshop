<?php
declare(strict_types=1);

namespace DomainShop\Entity;

final class Order
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $domainName;

    /**
     * @var string
     */
    private $ownerName;

    /**
     * @var string
     */
    private $ownerEmailAddress;

    /**
     * @var string
     */
    private $payInCurrency;

    /**
     * @var bool
     */
    private $wasPaid = false;

    /**
     * @var int
     */
    private $amount;

    /**
     * Order constructor.
     * @param string $domainName
     * @param string $ownerName
     * @param string $emailAddress
     * @param string $currency
     */
    public function __construct(
        string $domainName,
        string $ownerName,
        string $emailAddress,
        string $currency
    )
    {
        $this->domainName = $domainName;
        $this->ownerName = $ownerName;
        $this->ownerEmailAddress = $emailAddress;
        $this->payInCurrency = $currency;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getDomainName(): string
    {
        return $this->domainName;
    }

    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    public function getOwnerEmailAddress(): string
    {
        return $this->ownerEmailAddress;
    }

    public function wasPaid(): bool
    {
        return $this->wasPaid;
    }

    public function getDomainNameExtension(): string
    {
        $parts = explode('.', $this->getDomainName());
        return '.' . $parts[1];
    }

    public function setPayInCurrency(string $currency): void
    {
        $this->payInCurrency = $currency;
    }

    public function getPayInCurrency(): string
    {
        return $this->payInCurrency;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param string $currency
     * @param int $amount
     */
    public function pay(string $currency, int $amount)
    {
        $this->payInCurrency = $currency;
        $this->amount = $amount;
        $this->wasPaid = true;
    }
}
