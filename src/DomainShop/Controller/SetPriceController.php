<?php
declare(strict_types=1);

namespace DomainShop\Controller;

use DomainShop\Entity\Pricing;
use DomainShop\Repository\Pricing\PricingRepositoryInterface;
use DomainShop\Service\SetPricingService;
use Zend\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class SetPriceController implements MiddlewareInterface
{
    /**
     * @var PricingRepositoryInterface
     */
    private $pricingRepository;

    /**
     * @var SetPricingService
     */
    private $setPricingService;

    public function __construct(PricingRepositoryInterface $pricingRepository, SetPricingService $setPricingService)
    {
        $this->pricingRepository = $pricingRepository;
        $this->setPricingService = $setPricingService;
    }

    public function __invoke(Request $request, Response $response, callable $out = null)
    {
        $submittedData = $request->getParsedBody();

        $this->setPricingService->setPricing(
            $submittedData['currency'],
            (int)$submittedData['amount'],
            $submittedData['extension']
        );

        return $response;
    }
}
