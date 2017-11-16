<?php
declare(strict_types=1);

namespace Test\Integration;

use DomainShop\Repository\Pricing\PricingRepository;
use PHPUnit\Framework\TestCase;
use DomainShop\Entity\Pricing;

final class PricingRepositoryTest extends TestCase
{
    public function setUp()
    {
        $repository = new PricingRepository();
        $pricing = new Pricing('USD', 20, '.com');
        $repository->save($pricing);
    }

    /**
     * @test
     */
    public function it_saves_pricing()
    {
        $repository = new PricingRepository();
        $pricing = new Pricing('AUD', 30, '.net');

        $repository->save($pricing);
        $savedPricing = $repository->getPricing('.net');

        $this->assertNotNull($savedPricing);
    }

    public function it_gets_pricing_by_domain_extension()
    {
        $repository = new PricingRepository();
        $pricing = $repository->getPricing('.com');

        $expectedPricing = new Pricing('USD', 20, '.com');

        $this->assertEquals($pricing, $expectedPricing);
    }
}
