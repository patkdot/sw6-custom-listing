<?php
declare(strict_types=1);

namespace CustomListing\Subscriber\Storefront;

use CustomListing\Service\BoughtVariantsService;
use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OrderSubscriber implements EventSubscriberInterface
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
            CheckoutOrderPlacedEvent::class => 'onCheckoutOrderPlacedEvent',
        ];
    }

    public function onCheckoutOrderPlacedEvent(CheckoutOrderPlacedEvent $event): void
    {
        $boughtVariants = $this->listingService->buildBoughtVariantsFromOrder($event->getOrder());
        $this->listingService->setBoughtVariantsSessionAttribute($boughtVariants);
    }
}
