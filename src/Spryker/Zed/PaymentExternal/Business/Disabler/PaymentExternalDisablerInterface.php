<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Disabler;

use Generated\Shared\Transfer\PaymentMethodTransfer;

interface PaymentExternalDisablerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function disableExternalPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): void;
}
