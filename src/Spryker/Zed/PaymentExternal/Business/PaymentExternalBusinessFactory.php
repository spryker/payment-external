<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business;

use Spryker\Client\PaymentExternal\PaymentExternalClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\PaymentExternal\Business\Disabler\PaymentExternalDisabler;
use Spryker\Zed\PaymentExternal\Business\Disabler\PaymentExternalDisablerInterface;
use Spryker\Zed\PaymentExternal\Business\Enabler\PaymentExternalEnabler;
use Spryker\Zed\PaymentExternal\Business\Enabler\PaymentExternalEnablerInterface;
use Spryker\Zed\PaymentExternal\Business\Expander\PaymentExternalMethodQueryExpander;
use Spryker\Zed\PaymentExternal\Business\Expander\PaymentExternalMethodQueryExpanderInterface;
use Spryker\Zed\PaymentExternal\Business\Filter\PaymentMethodsFilter;
use Spryker\Zed\PaymentExternal\Business\Filter\PaymentMethodsFilterInterface;
use Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGenerator;
use Spryker\Zed\PaymentExternal\Business\Generator\PaymentMethodKeyGeneratorInterface;
use Spryker\Zed\PaymentExternal\Business\Hook\OrderPostSaveHook;
use Spryker\Zed\PaymentExternal\Business\Hook\OrderPostSaveHookInterface;
use Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapper;
use Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapperInterface;
use Spryker\Zed\PaymentExternal\Business\Mapper\QuoteDataMapper;
use Spryker\Zed\PaymentExternal\Business\Mapper\QuoteDataMapperInterface;
use Spryker\Zed\PaymentExternal\Business\Reader\OrderReader;
use Spryker\Zed\PaymentExternal\Business\Reader\OrderReaderInterface;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToLocaleFacadeInterface;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToSalesFacadeInterface;
use Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToStoreReferenceFacadeBridge;
use Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilTextServiceInterface;
use Spryker\Zed\PaymentExternal\PaymentExternalDependencyProvider;

/**
 * @method \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 */
class PaymentExternalBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Enabler\PaymentExternalEnablerInterface
     */
    public function createPaymentExternalCreator(): PaymentExternalEnablerInterface
    {
        return new PaymentExternalEnabler(
            $this->getPaymentFacade(),
            $this->createPaymentMethodKeyGenerator(),
            $this->createPaymentMethodEventMapper(),
        );
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Disabler\PaymentExternalDisablerInterface
     */
    public function createPaymentExternalDeleter(): PaymentExternalDisablerInterface
    {
        return new PaymentExternalDisabler(
            $this->getEntityManager(),
            $this->createPaymentMethodKeyGenerator(),
            $this->createPaymentMethodEventMapper(),
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
     * @return \Spryker\Zed\PaymentExternal\Business\Mapper\QuoteDataMapperInterface
     */
    public function createQuoteDataMapper(): QuoteDataMapperInterface
    {
        return new QuoteDataMapper();
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Hook\OrderPostSaveHookInterface
     */
    public function createOrderPostSaveHook(): OrderPostSaveHookInterface
    {
        return new OrderPostSaveHook(
            $this->createQuoteDataMapper(),
            $this->getLocaleFacade(),
            $this->getPaymentFacade(),
            $this->getPaymentExternalClient(),
            $this->getConfig(),
            $this->getStoreReferenceFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Reader\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader($this->getSalesFacade());
    }

    /**
     * @return \Spryker\Client\PaymentExternal\PaymentExternalClientInterface
     */
    public function getPaymentExternalClient(): PaymentExternalClientInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::CLIENT_PAYMENT_EXTERNAL);
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToLocaleFacadeInterface
     */
    public function getLocaleFacade(): PaymentExternalToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToPaymentFacadeInterface
     */
    public function getPaymentFacade(): PaymentExternalToPaymentFacadeInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::FACADE_PAYMENT);
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToSalesFacadeInterface
     */
    public function getSalesFacade(): PaymentExternalToSalesFacadeInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilTextServiceInterface
     */
    public function getUtilTextService(): PaymentExternalToUtilTextServiceInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::SERVICE_UTIL_TEXT);
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Business\Mapper\PaymentMethodEventMapperInterface
     */
    public function createPaymentMethodEventMapper(): PaymentMethodEventMapperInterface
    {
        return new PaymentMethodEventMapper();
    }

    /**
     * @return \Spryker\Zed\PaymentExternal\Dependency\Facade\PaymentExternalToStoreReferenceFacadeBridge
     */
    public function getStoreReferenceFacade(): PaymentExternalToStoreReferenceFacadeBridge
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::FACADE_STORE_REFERENCE);
    }
}
