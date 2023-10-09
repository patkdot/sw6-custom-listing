<?php declare(strict_types=1);

namespace CustomListing\Controller;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(defaults={"_routeScope"={"storefront"}})
 */
class AjaxController extends StorefrontController
{
    /**
     * @Route("/bought-variants", name="frontend.custom-listing.bought-variants", methods={"GET"})
     * @throws \JsonException
     */
    public function boughtVariants(Request $request, SalesChannelContext $context): Response
    {
        return new Response(json_encode(
            $request->getSession()->get('boughtVariants'),
            JSON_THROW_ON_ERROR
        ));
    }
}
