<?php

namespace DomainShop\Repository\Order;

use DomainShop\Entity\Order;

class FakeOrderRepository implements OrderRepositoryInterface
{
    private $orders = [];

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->orders;
    }

    /**
     * @param string $orderId
     */
    public function findById(string $orderId)
    {
        return $this->orders[$orderId];
    }

    /**
     * @param Order $order
     */
    public function save(Order $order): void
    {
        $this->orders[$order->id()] = $order;
    }
}
