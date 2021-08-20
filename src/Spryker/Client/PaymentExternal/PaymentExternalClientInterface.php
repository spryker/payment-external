<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentExternal;

use Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer;

interface PaymentExternalClientInterface
{
    /**
     * Specification:
     * - Makes a request from given PaymentExternalTokenRequestTransfer.
     * - Sends a request to an external payment server to generate a token.
     * - Returns a PaymentExternalTokenResponseTransfer with the received data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer
     */
    public function generatePaymentExternalToken(
        PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer
    ): PaymentExternalTokenResponseTransfer;
}
