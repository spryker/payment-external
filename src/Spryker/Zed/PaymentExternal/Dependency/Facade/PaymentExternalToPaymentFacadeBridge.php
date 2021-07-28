<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Dependency\Facade;

use Generated\Shared\Transfer\PaymentMethodResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderResponseTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;

class PaymentExternalToPaymentFacadeBridge implements PaymentExternalToPaymentFacadeInterface
{
    /**
     * @var \Spryker\Zed\Payment\Business\PaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @param \Spryker\Zed\Payment\Business\PaymentFacadeInterface $paymentFacade
     */
    public function __construct($paymentFacade)
    {
        $this->paymentFacade = $paymentFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderResponseTransfer
     */
    public function createPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): PaymentProviderResponseTransfer
    {
        return $this->paymentFacade->createPaymentProvider($paymentProviderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function createPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodResponseTransfer
    {
        return $this->paymentFacade->createPaymentMethod($paymentMethodTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer|null
     */
    public function findPaymentProvider(PaymentProviderTransfer $paymentProviderTransfer): ?PaymentProviderTransfer
    {
        return $this->paymentFacade->findPaymentProvider($paymentProviderTransfer);
    }
}
