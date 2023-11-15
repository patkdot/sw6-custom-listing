<?php

namespace CustomListing\Validation;


use CustomListing\Validation\Constraint\CustomerPhoneNumber;
use Shopware\Core\Checkout\Customer\Validation\AddressValidationFactory;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidationFactoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

readonly class AddressValidationFactoryDecorator implements DataValidationFactoryInterface
{
    public function __construct(
        private AddressValidationFactory $addressValidationFactory
    ) {
    }

    public function getDecorated(): AddressValidationFactory
    {
        return $this->addressValidationFactory;
    }

    public function create(SalesChannelContext $context): DataValidationDefinition
    {
        $definition = $this->addressValidationFactory->create($context);
        $definition->add('phoneNumber', new CustomerPhoneNumber());

        return $definition;
    }

    public function update(SalesChannelContext $context): DataValidationDefinition
    {
        $definition = $this->addressValidationFactory->update($context);
        $definition->add('phoneNumber', new CustomerPhoneNumber());

        return $definition;
    }
}
