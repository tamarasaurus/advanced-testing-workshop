<?php


namespace DomainShop\Service;

use DomainShop\Entity\Order;
use DomainShop\ExchangeRate\ExchangeRateCalculator;
use DomainShop\Repository\Order\OrderRepositoryInterface;
use DomainShop\Repository\Pricing\PricingRepositoryInterface;

class RegisterService
{

    /**
     * @var ExchangeRateCalculator
     */
    private $exchangeRateCalculator;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var PricingRepositoryInterface
     */
    private $pricingRepository;

    public function __construct(
        ExchangeRateCalculator $exchangeRateCalculator,
        OrderRepositoryInterface $orderRepository,
        PricingRepositoryInterface $pricingRepository
    )
    {
        $this->exchangeRateCalculator = $exchangeRateCalculator;
        $this->orderRepository = $orderRepository;
        $this->pricingRepository = $pricingRepository;
    }

    /**
     * @param string $domainName
     * @param string $name
     * @param string $emailAddress
     * @param string $currency
     * @return string
     */
    public function registerDomain(
        string $domainName,
        string $name,
        string $emailAddress,
        string $currency
    ): Order {
        $orderId = count($this->orderRepository->findAll()) + 1;

        $order = new Order($domainName, $name, $emailAddress, $currency);
        $order->setId($orderId);

        $pricing = $this->pricingRepository->getPricing($order->getDomainNameExtension());

        if ($order->getPayInCurrency() !== $pricing->getCurrency()) {
            $exchangeRate = $this->exchangeRateCalculator->getExchangeRate($pricing->getCurrency(), $order->getPayInCurrency());
            $amount = (int)round($pricing->getAmount() * $exchangeRate);
        } else {
            $amount = $pricing->getAmount();
        }

        $order->setAmount($amount);
        $this->orderRepository->save($order);

        return $order;
    }
}
