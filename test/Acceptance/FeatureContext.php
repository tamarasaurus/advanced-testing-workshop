<?php

namespace Test\Acceptance;

use Assert\Assert;
use Behat\Behat\Context\Context;
use DomainShop\Entity\Order;
use DomainShop\Entity\Pricing;
use DomainShop\Repository\Pricing\FakePricingRepository;
use DomainShop\Service\RegisterService;
use DomainShop\ExchangeRate\FakeExchangeRate;
use DomainShop\Service\PayService;
use DomainShop\Repository\Order\FakeOrderRepository;
use DomainShop\Service\SetPricingService;

final class FeatureContext implements Context
{
    /**
     * @var RegisterService
     */
    private $registerService;

    /**
     * @var Order
     */
    private $order;

    /**
     * @var PayService
     */
    private $payService;

    /**
     * @var SetPricingService
     */
    private $setPricingService;

    /**
     * @var Pricing
     */
    private $pricing;

    /**
     * @var FakeExchangeRate
     */
    private $exchangeRateCalculator;

    /**
     * @var FakePricingRepository
     */
    private $fakePricingRepository;

    private $fakeOrderRepository;

    public function __construct()
    {
        $this->fakePricingRepository = new FakePricingRepository();
        $this->fakeOrderRepository = new FakeOrderRepository();

        $this->exchangeRateCalculator = new FakeExchangeRate();
        $this->registerService = new RegisterService(
            $this->exchangeRateCalculator,
            $this->fakeOrderRepository,
            $this->fakePricingRepository
        );

        $this->payService = new PayService($this->fakeOrderRepository);
        $this->setPricingService = new SetPricingService($this->fakePricingRepository);
    }

    /**
     * @Given /^I register "([^"]*)" to "([^"]*)" with email address "([^"]*)" and I want to pay in USD$/
     */
    public function iRegisterToWithEmailAddressAndIWantToPayInUSD($domainName, $name, $emailAddress)
    {
        $order = $this->registerService->registerDomain($domainName, $name, $emailAddress, 'USD');

        $this->order = $order;
    }

    /**
     * @Given /^I pay (\d+)\.(\d+) USD for it$/
     */
    public function iPayUSDForIt()
    {
       $this->payService->pay($this->order);
    }

    /**
     * @Then /^the order was paid$/
     */
    public function theOrderWasPaid()
    {
        return Assert::that($this->order->wasPaid())->true();
    }

    /**
     * @Given /^a \.com domain name costs EUR (\d+\.\d+)$/
     */
    public function aComDomainNameCostsEUR($amount)
    {
        $pricing = new Pricing('EUR', (float) $amount, '.com');

        $this->fakePricingRepository->save($pricing);
    }

    /**
     * @Given /^the exchange rate EUR to USD is (\d+)\.(\d+)$/
     */
    public function theExchangeRateEURToUSDIs()
    {
        $rate = $this->exchangeRateCalculator->getExchangeRate('EUR', 'USD');

        return Assert::that($rate)->eq(1.156);
    }


}
