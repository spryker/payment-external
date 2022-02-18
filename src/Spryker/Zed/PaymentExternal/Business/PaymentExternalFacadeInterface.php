<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderCancelRequestTransfer;
use Generated\Shared\Transfer\OrderCancelResponseTransfer;
use Generated\Shared\Transfer\OrderFilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentExternalFacadeInterface
{
    /**
     * Specification:
     * - Requires PaymentMethodTransfer.labelName transfer field to be set.
     * - Requires PaymentMethodTransfer.groupName transfer field to be set.
     * - Requires PaymentMethodTransfer.checkoutOrderTokenUrl transfer field to be set.
     * - Requires PaymentMethodTransfer.checkoutRedirectUrl transfer field to be set.
     * - Creates payment provider if respective provider doesn't exist in DB
     * - Creates payment method if the payment method with provided key doesn't exist in DB.
     * - Updates payment method otherwise.
     * - Sets payment method `is_active` flag to false if it already exists.
     * - Returns PaymentMethod transfer filled with payment method data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function enableExternalPaymentMethod(PaymentMethodAddedTransfer $paymentMethodAddedTransfer): PaymentMethodTransfer;

    /**
     * Specification:
     * - Requires PaymentMethodTransfer.labelName transfer field to be set.
     * - Requires PaymentMethodTransfer.groupName transfer field to be set.
     * - Uses the specified data to find a payment method.
     * - Sets payment method `is_deleted` flag to true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return void
     */
    public function disableExternalPaymentMethod(PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer): void;

    /**
     * Specification:
     * - Filters payment methods.
     * - Returns only methods with a property isDeleted != true
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer): PaymentMethodsTransfer;

    /**
     * Specification:
     * - Builds QueryCriteria transfer to filter payment methods with the `is_deleted` flag set to true.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildNotDeletedPaymentMethodTableQueryCriteria(): QueryCriteriaTransfer;

    /**
     * Specification:
     * - Check whether the given order has a payment method external selected.
     * - Terminates hook execution if not.
     * - Receives all the necessary information about the payment method external.
     * - Sends a request with all pre-selected quote fields using PaymentMethodTransfer.checkoutOrderTokenUrl.
     * - If the response is free of errors, uses PaymentMethodTransfer.checkoutRedirectUrl and response data to build a redirect URL.
     * - Updates CheckoutResponseTransfer with errors or the redirect URL according to response received.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function executeOrderPostSaveHook(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): void;

    /**
     * Specification:
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
