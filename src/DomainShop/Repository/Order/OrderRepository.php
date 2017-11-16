<?php

namespace DomainShop\Repository\Order;

use Common\Persistence\Database;
use DomainShop\Entity\Order;

class OrderRepository implements OrderRepositoryInterface
{

    /**
     * @return array
     */
    public function findAll()
    {
        return Database::retrieveAll(Order::class);
    }

    /**
     * @param string $orderId
     */
    public function findById(string $orderId)
    {
        return Database::retrieve(Order::class, (string) $orderId);
    }

    /**
     * @param Order $order
     */
    public function save(Order $order): void
    {
        Database::persist($order);
    }
}
