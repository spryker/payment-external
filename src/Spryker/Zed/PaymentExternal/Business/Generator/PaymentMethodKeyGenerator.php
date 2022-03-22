<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Generator;

use Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilTextServiceInterface;

class PaymentMethodKeyGenerator implements PaymentMethodKeyGeneratorInterface
{
    /**
     * @var \Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\PaymentExternal\Dependency\Service\PaymentExternalToUtilTextServiceInterface $utilTextService
     */
    public function __construct(PaymentExternalToUtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param string $paymentProviderName
     * @param string $paymentMethodName
     * @param string $storeName
     *
     * @return string
     */
    public function generatePaymentMethodKey(string $paymentProviderName, string $paymentMethodName, string $storeName): string
    {
        return $this->utilTextService->generateSlug(
            sprintf('%s %s %s', $paymentProviderName, $paymentMethodName, $storeName),
        );
    }
}
