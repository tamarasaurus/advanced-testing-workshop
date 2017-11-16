<?php

namespace DomainShop\Repository\Pricing;

use Common\Persistence\Database;
use DomainShop\Entity\Pricing;

class PricingRepository implements PricingRepositoryInterface
{
    /**
     * @param string $domainNameExtension
     */
    public function getPricing(string $domainNameExtension) {
        return Database::retrieve(Pricing::class, $domainNameExtension);
    }

    /**
     * @param Pricing $pricing
     */
    public function save(Pricing $pricing): void {
        Database::persist($pricing);
    }

}
