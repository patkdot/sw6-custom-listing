<?php

declare(strict_types=1);

namespace CustomListing\Subscriber\Storefront;

use CustomListing\Service\BoughtVariantsService;
use CustomListing\Validation\Constraint\CustomerPhoneNumber;
use Shopware\Core\Framework\Validation\BuildValidationEvent;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\System\SalesChannel\Event\SalesChannelContextRestoredEvent;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ValidationSubscriber implements EventSubscriberInterface
{

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'framework.validation.address.create' => 'onBuildValidationEvent',
            'framework.validation.address.update' => 'onBuildValidationEvent',
        ];
    }

    public function onBuildValidationEvent(
        BuildValidationEvent $event,
        string $name
    ): void {
        $event->getDefinition()->add('phoneNumber', new CustomerPhoneNumber());
    }


}
