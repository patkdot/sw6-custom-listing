<?php
declare(strict_types=1);

namespace CustomListing\Service;

use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use Symfony\Component\HttpFoundation\RequestStack;

class BoughtVariantsService
{
    public const BOUGHT_VARIANTS_SESSION_KEY = 'boughtVariants';

    public function __construct(
        protected EntityRepository $orderRepository,
        protected RequestStack $requestStack
    ) {
    }

    public function getBoughtVariantsForCustomer(CustomerEntity $customer, Context $context): array
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('order.orderCustomer.customerId', $customer->getId()));
        $criteria->addFilter(
            new NotFilter(
                NotFilter::CONNECTION_OR,
                [
                    // todo: find cancelled constant
                    new EqualsFilter('order.stateMachineState.technicalName', 'cancelled')
                ]
            )
        );
        $criteria->addAssociation('lineItems');
        /** @var OrderEntity[] $orders */
        $orders = $this->orderRepository->search($criteria, $context);
        $boughtVariants = [];
        foreach ($orders as $order) {
            $boughtVariants = $this->buildBoughtVariantsFromOrder($order, $boughtVariants);
        }

        return $boughtVariants;
    }

    public function setBoughtVariantsSessionAttribute(array $boughtVariants): void
    {
        $boughtVariantsSession = $this->requestStack->getCurrentRequest()?->getSession()->get('boughtVariants');
        foreach ($boughtVariants as $productNumber => $quantity) {
            if (isset($boughtVariantsSession[$productNumber])) {
                $boughtVariantsSession[$productNumber] += $quantity;
            } else {
                $boughtVariantsSession[$productNumber] = $quantity;
            }
        }
        $this->requestStack->getCurrentRequest()
            ?->getSession()->set(self::BOUGHT_VARIANTS_SESSION_KEY, $boughtVariantsSession);
//        dd($this->requestStack->getCurrentRequest()?->getSession()->get(self::BOUGHT_VARIANTS_SESSION_KEY));
    }

    public function buildBoughtVariantsFromOrder(OrderEntity $order, array $boughtVariants = []): array
    {
        foreach ($order->getLineItems() as $lineItem) {
            if (isset($boughtVariants[$lineItem->getPayload()['productNumber']])) {
                $boughtVariants[$lineItem->getPayload()['productNumber']] += $lineItem->getQuantity();
            } else {
                $boughtVariants[$lineItem->getPayload()['productNumber']] = $lineItem->getQuantity();
            }
        }

        return $boughtVariants;
    }

    public function clearBoughtVariantsSessionAttribute(): void
    {
        $this->requestStack->getCurrentRequest()?->getSession()->remove(self::BOUGHT_VARIANTS_SESSION_KEY);
    }
}
