<?php

use DomainShop\Clock\Clock;
use DomainShop\ExchangeRate\FakeExchangeRate;
use DomainShop\ExchangeRate\RealExchangeRate;
use DomainShop\Service\RegisterService;
use DomainShop\Service\PayService;
use DomainShop\Repository\Order\OrderRepository;
use DomainShop\Repository\Pricing\PricingRepository;
use DomainShop\Repository\Pricing\FakePricingRepository;
use DomainShop\Repository\Order\FakeOrderRepository;
use DomainShop\Controller\CheckAvailabilityController;
use DomainShop\Controller\FinishController;
use DomainShop\Controller\HomepageController;
use DomainShop\Controller\PayController;
use DomainShop\Controller\RegisterController;
use DomainShop\Controller\SetPriceController;
use DomainShop\Resources\Views\TwigTemplates;
use Interop\Container\ContainerInterface;
use Symfony\Component\Debug\Debug;
use Xtreamwayz\Pimple\Container;
use Zend\Diactoros\Response;
use Zend\Expressive\Application;
use Zend\Expressive\Container\ApplicationFactory;
use Zend\Expressive\Helper\ServerUrlHelper;
use Zend\Expressive\Helper\UrlHelper;
use Zend\Expressive\Router\FastRouteRouter;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Twig\TwigRendererFactory;
use Zend\Stratigility\Middleware\NotFoundHandler;
use DomainShop\Service\SetPricingService;

Debug::enable();

$container = new Container();

$applicationEnv = getenv('APPLICATION_ENV') ?: 'development';

$container['config'] = [
    'middleware_pipeline' => [
        'routing' => [
            'middleware' => array(
                ApplicationFactory::ROUTING_MIDDLEWARE,
                ApplicationFactory::DISPATCH_MIDDLEWARE,
            ),
            'priority' => 1,
        ],
        [
            'middleware' => NotFoundHandler::class,
            'priority' => -1,
        ],
    ],
    'debug' => $applicationEnv !== 'production',
    'final_handler' => [
        'options' => [
            'env' => $applicationEnv,
            'onerror' => function(\Throwable $throwable) {
                error_log((string)$throwable);
            }
        ]
    ],
    'templates' => [
        'extension' => 'html.twig',
        'paths' => [
            TwigTemplates::getPath()
        ]
    ],
    'twig' => [
        'globals' => [
            'applicationEnv' => $applicationEnv
        ]
    ],
    'routes' => [
        [
            'name' => 'homepage',
            'path' => '/',
            'middleware' => HomepageController::class,
            'allowed_methods' => ['GET']
        ],
        [
            'name' => 'check_availability',
            'path' => '/check-availability',
            'middleware' => CheckAvailabilityController::class,
            'allowed_methods' => ['POST']
        ],
        [
            'name' => 'register',
            'path' => '/register',
            'middleware' => RegisterController::class,
            'allowed_methods' => ['POST']
        ],
        [
            'name' => 'pay',
            'path' => '/pay/{orderId}',
            'middleware' => PayController::class,
            'allowed_methods' => ['GET', 'POST']
        ],
        [
            'name' => 'finish',
            'path' => '/finish/{orderId}',
            'middleware' => FinishController::class,
            'allowed_methods' => ['GET']
        ],
        [
            'name' => 'set_price',
            'path' => '/set-price',
            'middleware' => SetPriceController::class,
            'allowed_methods' => ['POST']
        ],
    ]
];

/*
 * Zend Expressive Application
 */
$container[RouterInterface::class] = function () {
    return new FastRouteRouter();
};
$container[Application::class] = new ApplicationFactory();
$container[NotFoundHandler::class] = function() {
    return new NotFoundHandler(new Response());
};

/*
 * Templating
 */
$container[TemplateRendererInterface::class] = new TwigRendererFactory();
$container[ServerUrlHelper::class] = function () {
    return new ServerUrlHelper();
};
$container[UrlHelper::class] = function (ContainerInterface $container) {
    return new UrlHelper($container[RouterInterface::class]);
};

/*
 * Controllers
 */
$container[HomepageController::class] = function (ContainerInterface $container) {
    return new HomepageController($container->get(TemplateRendererInterface::class));
};
$container[CheckAvailabilityController::class] = function (ContainerInterface $container) {
    return new CheckAvailabilityController($container->get(TemplateRendererInterface::class));
};

$container[Clock::class] = function () {
    $time = new \DateTime();

    if (getenv('APPLICATION_ENV') === 'testing') {
        $time = new \DateTime(getenv('SERVER_TIME'));
    }

    return new Clock($time);
};

$container[FakeExchangeRate::class] = function (ContainerInterface $container) {
    return new FakeExchangeRate();
};

$container[RealExchangeRate::class] = function (ContainerInterface $container) {
    return new RealExchangeRate($container->get(Clock::class));
};

$container[RegisterService::class] = function (ContainerInterface $container) {
    $calculator = $container->get(RealExchangeRate::class);

    if (getenv('APPLICATION_ENV') === 'testing') {
        $calculator = $container->get(FakeExchangeRate::class);
    }

    return new RegisterService(
        $calculator,
        $container->get(OrderRepository::class),
        $container->get(PricingRepository::class)
    );
};

$container[OrderRepository::class] = function ($applicationEnv) {
    if ($applicationEnv === 'testing') {
        return new FakeOrderRepository();
    }
    return new OrderRepository();
};

$container[PricingRepository::class] = function ($applicationEnv) {
    if ($applicationEnv === 'testing') {
        return new FakePricingRepository();
    }
    return new PricingRepository();
};

$container[PayService::class] = function (ContainerInterface $container) {
    return new PayService($container->get(OrderRepository::class));
};

$container[RegisterController::class] = function (ContainerInterface $container) {
    return new RegisterController(
        $container->get(RouterInterface::class),
        $container->get(TemplateRendererInterface::class),
        $container->get(RegisterService::class)
    );
};

$container[PayController::class] = function (ContainerInterface $container) {
    return new PayController(
        $container->get(RouterInterface::class),
        $container->get(TemplateRendererInterface::class),
        $container->get(PayService::class),
        $container->get(OrderRepository::class)
    );
};
$container[FinishController::class] = function (ContainerInterface $container) {
    return new FinishController(
        $container->get(TemplateRendererInterface::class)
    );
};

$container[SetPricingService::class] = function (ContainerInterface $container) {
    return new SetPricingService(
        $container->get(PricingRepository::class)
    );
};

$container[SetPriceController::class] = function (ContainerInterface $container) {
    return new SetPriceController(
        $container->get(PricingRepository::class),
        $container->get(SetPricingService::class)
    );
};


return $container;
