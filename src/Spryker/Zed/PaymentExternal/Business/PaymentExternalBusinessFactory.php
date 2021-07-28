<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PaymentExternal\Business\Creator\PaymentExternalCreator;
use Spryker\Zed\PaymentExternal\Business\Creator\PaymentExternalCreatorInterface;
use Spryker\Zed\PaymentExternal\Business\Deleter\PaymentExternalDeleter;
use Spryker\Zed\PaymentExternal\Business\Deleter\PaymentExternalDeleterInterface;
use Spryker\Zed\PaymentExternal\Business\Expander\PaymentExternalMethodQueryExpander;
use Spryker\Zed\PaymentExternal\Business\Expander\PaymentExternalMethodQueryExpanderInterface;
use Spryker\Zed\PaymentExternal\Business\Filter\PaymentMethodsFilter;
use Spryker\Zed\PaymentExternal\Business\Filter\PaymentMethodsFilterInterface;
use Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGenerator;
use Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface;
use Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilEncodingServiceInterface;
use Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilTextServiceInterface;
use Spryker\Zed\PaymentExternal\PaymentExternalDependencyProvider;

/**
 * @method \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 */
class PaymentExternalBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Creator\PaymentExternalCreatorInterface
     */
    public function createPaymentExternalCreator(): PaymentExternalCreatorInterface
    {
        return new PaymentExternalCreator(
            $this->getPaymentFacade(),
            $this->getUtilEncodingService(),
            $this->createPaymentMethodKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Deleter\PaymentExternalDeleterInterface
     */
    public function createPaymentExternalDeleter(): PaymentExternalDeleterInterface
    {
        return new PaymentExternalDeleter(
            $this->getEntityManager(),
            $this->createPaymentMethodKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Filter\PaymentMethodsFilterInterface
     */
    public function createPaymentMethodsFilter(): PaymentMethodsFilterInterface
    {
        return new PaymentMethodsFilter();
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Expander\PaymentExternalMethodQueryExpanderInterface
     */
    public function createPaymentExternalMethodQueryExpander(): PaymentExternalMethodQueryExpanderInterface
    {
        return new PaymentExternalMethodQueryExpander();
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface
     */
    public function createPaymentMethodKeyGenerator(): PaymentMethodKeyGeneratorInterface
    {
        return new PaymentMethodKeyGenerator($this->getUtilTextService());
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface
     */
    public function getPaymentFacade(): PaymentExternalToPaymentFacadeInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::FACADE_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PaymentExternalToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilTextServiceInterface
     */
    public function getUtilTextService(): PaymentExternalToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
