<?php

namespace DomainShop\Repository\Pricing;

use DomainShop\Entity\Pricing;

interface PricingRepositoryInterface
{
    public function getPricing(string $domainNameExtension);
    public function save(Pricing $pricing): void;
}
