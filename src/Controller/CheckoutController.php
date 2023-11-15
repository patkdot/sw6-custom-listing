<?php
declare(strict_types=1);

namespace CustomListing\Controller;

use CustomListing\Service\CheckoutService;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class CheckoutController extends StorefrontController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutService $checkoutService
    ) {
    }

    #[Route(path: '/checkout/payment-shipping', name: 'frontend.checkout.payment-shipping', options: ['seo' => false], defaults: ['_noStore' => true], methods: ['GET'])]
    public function paymentShipping(Request $request, SalesChannelContext $context): Response
    {
        if (!$context->getCustomer()) {
            return $this->redirectToRoute('frontend.checkout.register.page');
        }
        $cart = $this->cartService->getCart($context->getToken(), $context);
        if ($cart->getLineItems()->count() === 0) {
            return $this->redirectToRoute('frontend.checkout.cart.page');
        }

        return $this->renderStorefront('@CustomListing/storefront/page/checkout/payment-shipping.html.twig', [
            'page' => [
                'cart' => $cart,
                'paymentMethods' => $this->checkoutService->getPaymentMethods($context),
                'shippingMethods' => $this->checkoutService->getShippingMethods($context),
            ]
        ]);
    }
}
