<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentExternal;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PaymentExternal\Dependency\Client\PaymentExternalToZedRequestClientInterface;
use Spryker\Client\PaymentExternal\Dependency\External\PaymentExternalToHttpClientAdapterInterface;
use Spryker\Client\PaymentExternal\Dependency\Service\PaymentExternalToUtilEncodingServiceInterface;
use Spryker\Client\PaymentExternal\Executor\PaymentExternalRequestExecutor;
use Spryker\Client\PaymentExternal\Executor\PaymentExternalRequestExecutorInterface;
use Spryker\Client\PaymentExternal\Zed\PaymentExternalStub;
use Spryker\Client\PaymentExternal\Zed\PaymentExternalStubInterface;

class PaymentExternalFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PaymentExternal\Executor\PaymentExternalRequestExecutorInterface
     */
    public function createPaymentExternalRequestExecutor(): PaymentExternalRequestExecutorInterface
    {
        return new PaymentExternalRequestExecutor(
            $this->getUtilEncodingService(),
            $this->getHttpClient(),
        );
    }

    /**
     * @return \Spryker\Client\PaymentExternal\Zed\PaymentExternalStubInterface
     */
    public function createZedPaymentExternalStub(): PaymentExternalStubInterface
    {
        return new PaymentExternalStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\PaymentExternal\Dependency\Service\PaymentExternalToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): PaymentExternalToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\PaymentExternal\Dependency\External\PaymentExternalToHttpClientAdapterInterface
     */
    public function getHttpClient(): PaymentExternalToHttpClientAdapterInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::CLIENT_HTTP);
    }

    /**
     * @return \Spryker\Client\PaymentExternal\Dependency\Client\PaymentExternalToZedRequestClientInterface
     */
    public function getZedRequestClient(): PaymentExternalToZedRequestClientInterface
    {
        return $this->getProvidedDependency(PaymentExternalDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
