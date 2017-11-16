<?php

namespace DomainShop\ExchangeRate;


interface ExchangeRateCalculator
{
    public function getExchangeRate(string $from, string $to): float;
}
