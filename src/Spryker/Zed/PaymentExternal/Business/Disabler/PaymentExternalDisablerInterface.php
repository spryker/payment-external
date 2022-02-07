<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Disabler;

use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;

interface PaymentExternalDisablerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return void
     */
    public function disableExternalPaymentMethod(PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer): void;
}
