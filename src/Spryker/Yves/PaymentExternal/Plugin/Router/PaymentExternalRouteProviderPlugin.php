<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PaymentExternal\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PaymentExternalRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    public const ROUTE_NAME_PAYMENT_ORDER_CANCEL = 'payment-order-cancel';
    public const ROUTE_NAME_PAYMENT_ORDER_SUCCESS = 'payment-order-success';

    /**
     * {@inheritDoc}
     * - Adds OrderCancelWidget module routes to RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addOrderSuccessRoute($routeCollection);
        $routeCollection = $this->addOrderCancelRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \Spryker\Yves\PaymentExternal\Controller\OrderSuccessController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addOrderSuccessRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/payment/order-success', 'PaymentExternal', 'OrderSuccess');
        $routeCollection->add(static::ROUTE_NAME_PAYMENT_ORDER_SUCCESS, $route);

        return $routeCollection;
    }

    /**
     * @uses \Spryker\Yves\PaymentExternal\Controller\OrderCancelController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addOrderCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildGetRoute('/payment/order-cancel', 'PaymentExternal', 'OrderCancel');
        $routeCollection->add(static::ROUTE_NAME_PAYMENT_ORDER_CANCEL, $route);

        return $routeCollection;
    }
}
