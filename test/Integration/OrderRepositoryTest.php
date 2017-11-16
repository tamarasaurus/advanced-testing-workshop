<?php
declare(strict_types=1);

namespace Test\Integration;

use PHPUnit\Framework\TestCase;
use DomainShop\Repository\Order\OrderRepository;
use DomainShop\Entity\Order;

final class OrderRepositoryTest extends TestCase
{
    public function setUp()
    {
        $repository = new OrderRepository();
        $order = new Order('.net', 'tamara', 'tamara@tam.com', 'AUD');
        $order->setId(456);
        $repository->save($order);
    }

    /**
     * @test
     */
    public function it_saves_an_order()
    {
        $repository = new OrderRepository();
        $order = new Order('google.com', 'tamara', 'tamara@tam.com', 'AUD');

        $order->pay('AUD', 10);
        $order->setId(123);

        $repository->save($order);
        $savedOrder = $repository->findById((string) 123);

        $this->assertEquals($order, $savedOrder);
    }

    public function it_gets_an_order_by_id()
    {
        $repository = new OrderRepository();
        $order = $repository->findById((string) 456);

        $this->assertNotNull($order);
    }
}
