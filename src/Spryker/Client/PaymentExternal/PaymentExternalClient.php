<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PaymentExternal;

use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer;
use Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PaymentExternal\PaymentExternalFactory getFactory()
 */
class PaymentExternalClient extends AbstractClient implements PaymentExternalClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentExternalTokenResponseTransfer
     */
    public function generatePaymentExternalToken(
        PaymentExternalTokenRequestTransfer $paymentExternalTokenRequestTransfer
    ): PaymentExternalTokenResponseTransfer {
        return $this->getFactory()
            ->createPaymentExternalRequestExecutor()
            ->generatePaymentExternalToken($paymentExternalTokenRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer
    {
        return $this->getFactory()
            ->createZedPaymentExternalStub()
            ->cancelOrder($orderCancelRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getGuestOrder(OrderFilterTransfer $orderFilterTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createZedPaymentExternalStub()
            ->getGuestOrder($orderFilterTransfer);
    }
}
