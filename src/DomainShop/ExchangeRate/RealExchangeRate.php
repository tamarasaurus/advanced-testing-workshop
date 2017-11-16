<?php
/**
 * Created by PhpStorm.
 * User: tamararobichet
 * Date: 05/02/2018
 * Time: 13:39
 */

namespace DomainShop\ExchangeRate;

use DomainShop\Clock\Clock;
use Swap\Builder;

class RealExchangeRate implements ExchangeRateCalculator
{
    /**
     * @var Clock
     */
    private $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    /**
     * @param string $from
     * @param string $to
     * @return float
     */
    public function getExchangeRate(string $from, string $to): float {
        $swap = (new Builder())
            ->add('fixer')
            ->build();

        $rate = $swap->historical($from . '/' . $to, $this->clock->getDateTime());

        return (float) $rate->getValue();
    }
}
