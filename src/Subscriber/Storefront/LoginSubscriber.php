<?php
declare(strict_types=1);

namespace CustomListing\Subscriber\Storefront;

use CustomListing\Service\BoughtVariantsService;
use Shopware\Core\System\SalesChannel\Event\SalesChannelContextRestoredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginSubscriber implements EventSubscriberInterface
{

    public function __construct(
        protected BoughtVariantsService $listingService,
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SalesChannelContextRestoredEvent::class => 'onSalesChannelContextRestoredEvent',
        ];
    }

    public function onSalesChannelContextRestoredEvent(SalesChannelContextRestoredEvent $event): void
    {
        $customer = $event->getRestoredSalesChannelContext()->getCustomer();
        if ($customer === null) {
            return;
        }
        $boughtVariants = $this->listingService
            ->getBoughtVariantsForCustomer($customer, $event->getContext());
        $this->listingService->clearBoughtVariantsSessionAttribute();
        $this->listingService->setBoughtVariantsSessionAttribute($boughtVariants);
    }
}
