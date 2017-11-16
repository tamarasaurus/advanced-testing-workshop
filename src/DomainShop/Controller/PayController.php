<?php
declare(strict_types=1);

namespace DomainShop\Controller;

use DomainShop\Entity\Order;
use DomainShop\Repository\Order\OrderRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Stratigility\MiddlewareInterface;
use DomainShop\Service\PayService;

final class PayController implements MiddlewareInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TemplateRendererInterface
     */
    private $renderer;

    /**
     * @var PayService
     */
    private $payService;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(RouterInterface $router, TemplateRendererInterface $renderer, PayService $payService,
                                OrderRepository $orderRepository)
    {
        $this->router = $router;
        $this->renderer = $renderer;
        $this->payService = $payService;
        $this->orderRepository = $orderRepository;
    }

    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $orderId = $request->getAttribute('orderId');

        /** @var Order $order */
        $order = $this->orderRepository->findById($orderId);

        if ($request->getMethod() === 'POST') {
            $submittedData = $request->getParsedBody();

            if (isset($submittedData['pay'])) {
                $this->payService->pay($order);
            }

            return new RedirectResponse(
                $this->router->generateUri('finish', ['orderId' => $orderId])
            );
        }

        $response->getBody()->write($this->renderer->render('pay.html.twig', [
            'orderId' => $orderId,
            'domainName' => $order->getDomainName(),
            'currency' => $order->getPayInCurrency(),
            'amount' => $order->getAmount()
        ]));

        return $response;
    }
}
