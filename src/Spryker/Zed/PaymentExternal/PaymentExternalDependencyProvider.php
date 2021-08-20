<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal;

use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToLocaleFacadeBridge;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeBridge;
use Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 */
class PaymentExternalDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PAYMENT_EXTERNAL = 'CLIENT_PAYMENT_EXTERNAL';

    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PAYMENT = 'FACADE_PAYMENT';

    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    public const PROPEL_QUERY_PAYMENT_METHOD = 'PROPEL_QUERY_PAYMENT_METHOD';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addPaymentExternalClient($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addPaymentFacade($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addPaymentMethodQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentExternalClient(Container $container): Container
    {
        $container->set(static::CLIENT_PAYMENT_EXTERNAL, function (Container $container) {
            return $container->getLocator()->paymentExternal()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new PaymentExternalToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentFacade(Container $container): Container
    {
        $container->set(static::FACADE_PAYMENT, function (Container $container) {
            return new PaymentExternalToPaymentFacadeBridge(
                $container->getLocator()->payment()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new PaymentExternalToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPaymentMethodQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PAYMENT_METHOD, $container->factory(function () {
            return SpyPaymentMethodQuery::create();
        }));

        return $container;
    }
}
