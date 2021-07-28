<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Deleter;

use Generated\Shared\Transfer\PaymentMethodTransfer;

interface PaymentExternalDeleterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function deletePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): void;
}
