<?php

namespace DomainShop\Service;

use DomainShop\Entity\Order;
use DomainShop\Repository\Order\OrderRepositoryInterface;

class PayService
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param string $orderId
     */
    public function pay(Order $order) {
        $order->pay($order->getPayInCurrency(), $order->getAmount());

        $this->orderRepository->save($order);
    }
}
