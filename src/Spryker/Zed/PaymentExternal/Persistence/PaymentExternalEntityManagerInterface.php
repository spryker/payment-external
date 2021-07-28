<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Persistence;

use Generated\Shared\Transfer\PaymentMethodTransfer;

interface PaymentExternalEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function deletePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): void;
}
