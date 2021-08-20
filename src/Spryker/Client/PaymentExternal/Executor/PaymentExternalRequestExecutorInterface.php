<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentExternal\Executor;

use Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer;

interface PaymentExternalRequestExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer
     */
    public function generatePaymentExternalToken(
        PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer
    ): PaymentExternalTokenResponseTransfer;
}
