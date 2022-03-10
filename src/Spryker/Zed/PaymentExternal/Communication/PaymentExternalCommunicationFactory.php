<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToStoreReferenceFacadeBridge;
use Spryker\Zed\PaymentExternal\PaymentExternalDependencyProvider;

/**
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 * @method \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 */
class PaymentExternalCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToStoreReferenceFacadeBridge
     */
    public function getStoreReferenceFacade(): PaymentExternalToStoreReferenceFacadeBridge
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::FACADE_STORE_REFERENCE);
    }
}
