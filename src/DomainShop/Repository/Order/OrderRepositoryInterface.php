<?php

namespace DomainShop\Repository\Order;

use DomainShop\Entity\Order;

interface OrderRepositoryInterface
{
    public function findAll();
    public function findById(string $orderId);
    public function save(Order $order);
}
