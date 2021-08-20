<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\PaymentExternal;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCartClientInterface;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCustomerClientInterface;
use Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToSalesClientInterface;

class PaymentExternalFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCustomerClientInterface
     */
    public function getCustomerClient(): PaymentExternalToCustomerClientInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToSalesClientInterface
     */
    public function getSalesClient(): PaymentExternalToSalesClientInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::CLIENT_SALES);
    }

    /**
     * @return \Spryker\Yves\PaymentExternal\Dependency\Client\PaymentExternalToCartClientInterface
     */
    public function getCartClient(): PaymentExternalToCartClientInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::CLIENT_CART);
    }
}
