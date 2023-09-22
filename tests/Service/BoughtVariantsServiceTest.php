<?php
declare(strict_types=1);

namespace CustomListing\Test\Service;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;

/**
 * @covers \CustomListing\Service\BoughtVariantsService
 */
class BoughtVariantsServiceTest extends TestCase
{
    use IntegrationTestBehaviour;

    protected EntityRepository $productRepository;
    protected EntityRepository $customerRepository;
    protected EntityRepository $addressRepository;
    protected EntityRepository $orderRepository;
    protected string $customerId;

    public function setUp(): void
    {
        // todo: do the below code globally in abstract class
        static::getKernel()::getConnection()->executeStatement('DELETE FROM product WHERE 1');
        static::getKernel()::getConnection()->executeStatement('DELETE FROM customer WHERE 1');
        static::getKernel()::getConnection()->executeStatement('DELETE FROM customer_address WHERE 1');
        static::getKernel()::getConnection()->executeStatement('DELETE FROM `order` WHERE 1');

        $this->productRepository = static::getContainer()->get('product.repository');
        $this->customerRepository = static::getContainer()->get('customer.repository');
        $this->addressRepository = static::getContainer()->get('customer_address.repository');
        $this->orderRepository = static::getContainer()->get('order.repository');
        $this->boughtVariantService = static::getContainer()->get('CustomListing\Service\BoughtVariantsService');

        $context = Context::createDefaultContext();
        $addressId = Uuid::randomHex();
        $productId = Uuid::randomHex();
        $this->customerId = Uuid::randomHex();
        $currencyId = Defaults::CURRENCY;//'018ab9a73e5672d7bf63ea717d5ab51f';
        $taxId = '018ab9a7484973908a85e5beb025267a';
        $salesChannelId = '018ab9a805557357bf5be95d3f4bfc96';
        $defaultPaymentId = '018ab9a747e8722cbfb7da66033b585e';
        $countryId = '018ab9a73e5672d7bf63ea717d5ab51f';
        $stateId = '018ab9a74b3472f293afe3dfaca82b4e';
        $shippingId = '018ab9a74823717d92f58cae6dcd2495';

        $this->productRepository->create([
            [
                'id' => $productId,
                "name" => "test",
                "productNumber" => "random",
                "stock" => 10,
                "taxId" => $taxId,
                "price" => [
                    [
                        "currencyId" => Defaults::CURRENCY,
                        "gross" => 15,
                        "net" => 10,
                        "linked" => false
                    ]
                ],
            ]
        ], $context);

        $this->customerRepository->create([
            [
                'id' => $this->customerId,
                'email' => 'pat.kdot@example.com',
                'firstName' => 'pat',
                'lastName' => 'kdot',
                'customerNumber' => '123456',
                'salesChannelId' => $salesChannelId,
                'groupId' => 'cfbd5018d38d41d8adca10d94fc8bdd6',
                'defaultPaymentMethodId' => $defaultPaymentId,
                'defaultBillingAddress' => [
                    'id' => $addressId,
                    'customerId' => $this->customerId,
                    'street' => 'street 1',
                    'zipcode' => '12345',
                    'city' => 'ddorf',
                    'countryId' => $countryId,
                    'firstName' => 'pat',
                    'lastName' => 'kdot',
                ],
                'defaultShippingAddress' => [
                    'id' => $addressId,
                    'customerId' => $this->customerId,
                    'street' => 'street 1',
                    'zipcode' => '12345',
                    'city' => 'ddorf',
                    'countryId' => $countryId,
                    'firstName' => 'pat',
                    'lastName' => 'kdot',
                ]
            ]
        ], $context);

        // todo order is not being created
        $event = $this->orderRepository->create([
            [
                'shippingCosts' => [
                    'unitPrice' => 10,
                    'totalPrice' => 10,
                    'quantity' => 1,
                    'calculatedTaxes' => [
                        [
                            'tax' => 10,
                            'taxRate' => 19,
                            'price' => 10,
                        ]
                    ],
                    'taxRules' => [
                        [
                            'taxRate' => 19,
                            'percentage' => 100,
                        ]
                    ],
                ],
                'orderNumber' => '10001',
                'orderDateTime' => new \DateTime(),
                'stateId' => $stateId,
                'customerId' => $this->customerId,
                'orderLineItems' => [
                    'productId' => $productId,
                    'quantity' => 1,
                ],
                'billingAddressId' => $addressId,
                'currencyId' => $currencyId,
                'salesChannelId' => $salesChannelId,
                'currencyFactor' => 1,
                'itemRounding' => [
                    'decimals' => 2,
                    'interval' => 0.01,
                    'roundForNet' => false,
                ],
                'totalRounding' => [
                    'decimals' => 2,
                    'interval' => 0.01,
                    'roundForNet' => false,
                ],
                'price' => [
                    'totalPrice' => 10,
                    'netPrice' => 10,
                    'positionPrice' => 10,
                    'rawTotal' => 10,
                    'taxStatus' => 'gross',
                    'taxRules' => [
                        [
                            'taxRate' => 19,
                            'percentage' => 100,
                        ]
                    ],
                    'calculatedTaxes' => [
                        [
                            'tax' => 10,
                            'taxRate' => 19,
                            'price' => 10,
                        ]
                    ],
                ],
            ]
        ], $context);
    }

    /**
     * @covers \CustomListing\Service\BoughtVariantsService::getBoughtVariantsForCustomer
     */
    public function testSessionHasBoughtVariantAfterLogin(): void
    {
        $context = Context::createDefaultContext();
        $customer = $this->customerRepository->search(
            (new Criteria([$this->customerId])),
            Context::createDefaultContext()
        )->first();

        $boughtVariants = $this->boughtVariantService->getBoughtVariantsForCustomer($customer, $context);
        //todo: add correct array
        static::assertSame([], $boughtVariants);
    }
}
