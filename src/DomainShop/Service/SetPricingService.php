<?php

namespace DomainShop\Service;

use DomainShop\Repository\Pricing\PricingRepositoryInterface;
use DomainShop\Entity\Pricing;

class SetPricingService
{
    /**
     * @var PricingRepositoryInterface
     */
    private $pricingRepository;

    public function __construct(PricingRepositoryInterface $pricingRepository)
    {
        $this->pricingRepository = $pricingRepository;
    }

    public function setPricing(string $currency, int $amount, string $extension): Pricing {
        $pricing = new Pricing($currency, $amount, $extension);

        $this->pricingRepository->save($pricing);

        return $pricing;
    }
}
