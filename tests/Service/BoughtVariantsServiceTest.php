<?php
declare(strict_types=1);

namespace CustomListing\Tests\Service;

use CustomListing\Tests\BaseTest;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

/**
 * @covers \CustomListing\Service\BoughtVariantsService
 */
class BoughtVariantsServiceTest extends BaseTest
{
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
        static::assertSame(['random' => 1], $boughtVariants);
    }
}
