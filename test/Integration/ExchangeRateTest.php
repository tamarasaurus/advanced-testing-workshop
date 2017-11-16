<?php
declare(strict_types=1);

namespace Test\Integration;

use PHPUnit\Framework\TestCase;
use DomainShop\ExchangeRate\RealExchangeRate;
use DomainShop\Clock\Clock;
use Assert\Assert;

final class ExchangeRateTest extends TestCase
{
    /**
     * @test
     */
    public function it_gets_the_exchange_rate()
    {
        $clock = new Clock(new \DateTime());
        $exchangeRateCalculator = new RealExchangeRate($clock);
        $rate = $exchangeRateCalculator->getExchangeRate('AUD', 'EUR');

        Assert::that($rate)->notEmpty($rate);
    }
}
