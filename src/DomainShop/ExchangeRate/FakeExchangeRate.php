<?php

namespace DomainShop\ExchangeRate;

class FakeExchangeRate implements ExchangeRateCalculator
{
    public function getExchangeRate(string $from, string $to): float {
        return 1.156;
    }
}
