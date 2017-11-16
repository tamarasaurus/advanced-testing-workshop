<?php
declare(strict_types=1);

namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use DomainShop\Entity\Pricing;

final class PricingTest extends TestCase
{
    /**
     * @test
     */
    public function it_sets_pricing_with_currency_amount_extension()
    {
        $extension = '.com';
        $currency = 'AUD';
        $amount = 100;

        $pricing = new Pricing($currency, $amount, $extension);

        $this->assertEquals($pricing->getCurrency(), $currency);
        $this->assertEquals($pricing->getAmount(), $amount);
        $this->assertEquals($pricing->getExtension(), $extension);
    }
}
