<?php

namespace DomainShop\Repository\Pricing;

use DomainShop\Entity\Pricing;

class FakePricingRepository implements PricingRepositoryInterface
{
    private $pricing = [];

    public function getPricing(string $domainNameExtension)
    {
        return $this->pricing[$domainNameExtension];
    }

    /**
     * @param Pricing $pricing
     */
    public function save(Pricing $pricing): void
    {
        $this->pricing[$pricing->getExtension()] = $pricing;
    }
}
