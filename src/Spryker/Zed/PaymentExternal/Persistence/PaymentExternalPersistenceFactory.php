<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Persistence;

use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\PaymentExternal\PaymentExternalDependencyProvider;

/**
 * @method \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface getEntityManager()
 */
class PaymentExternalPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery
     */
    public function getPaymentMethodQuery(): SpyPaymentMethodQuery
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::PROPEL_QUERY_PAYMENT_METHOD);
    }
}
