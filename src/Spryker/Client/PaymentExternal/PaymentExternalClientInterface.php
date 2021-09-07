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

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires `OrderCancelRequestTransfer.idSalesOrder` to be set.
     * - Retrieves OrderTransfer filtered by idSalesOrder.
     * - Checks OrderTransfer.isCancellable for true.
     * - Triggers a cancel event for found order items.
     * - Returns OrderCancelResponseTransfer with isSuccessful = true and found order transfer set on success.
     * - Returns OrderCancelResponseTransfer with isSuccessful = false and error message set otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderCancelRequestTransfer $orderCancelRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OrderCancelResponseTransfer
     */
    public function cancelOrder(OrderCancelRequestTransfer $orderCancelRequestTransfer): OrderCancelResponseTransfer;

    /**
     * Specification:
     * - Makes Zed request.
     * - Requires `OrderFilterTransfer.orderReference` to be set.
     * - Finds persisted order information using OrderFilterTransfer.
     * - Adds information about the order items to the found order.
     * - Hydrates order by calling HydrateOrderPlugins registered in project dependency provider.
     * - Hydrates order using quote level (BC) or item level shipping addresses.
     * - Returns an empty order if the customer reference in the found order is not NULL.
     * - Returns the found order with hydrated information otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderFilterTransfer $orderFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getGuestOrder(OrderFilterTransfer $orderFilterTransfer): OrderTransfer;
}
