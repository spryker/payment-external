<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Enabler;

use Generated\Shared\Transfer\PaymentMethodTransfer;

interface PaymentExternalEnablerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function enableExternalPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodTransfer;
}
