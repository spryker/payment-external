<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PaymentExternal;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCartClientBridge;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCustomerClientBridge;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToSalesClientBridge;

class PaymentExternalDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    
    /**
     * @var string
     */
    public const CLIENT_SALES = 'CLIENT_SALES';
    
    /**
     * @var string
     */
    public const CLIENT_CART = 'CLIENT_CART';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCustomerClient($container);
        $container = $this->addSalesClient($container);
        $container = $this->addCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new PaymentExternalToCustomerClientBridge($container->getLocator()->customer()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES, function (Container $container) {
            return new PaymentExternalToSalesClientBridge($container->getLocator()->sales()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART, function (Container $container) {
            return new PaymentExternalToCartClientBridge($container->getLocator()->cart()->client());
        });

        return $container;
    }
}
