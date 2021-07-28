<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;

interface PaymentExternalFacadeInterface
{
    /**
     * Specification:
     * - Requires PaymentMethodTransfer.labelName transfer field to be set.
     * - Requires PaymentMethodTransfer.groupName transfer field to be set.
     * - Requires PaymentMethodTransfer.checkoutOrderTokenUrl transfer field to be set.
     * - Requires PaymentMethodTransfer.checkoutRedirectUrl transfer field to be set.
     * - Creates payment provider if respective provider doesn't exist in DB
     * - Creates payment method.
     * - Returns PaymentMethod transfer filled with payment method data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function addPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodTransfer;

    /**
     * Specification:
     * - Requires PaymentMethodTransfer.labelName transfer field to be set.
     * - Requires PaymentMethodTransfer.groupName transfer field to be set.
     * - Uses the specified data to find a payment method.
     * - Sets payment method `is_deleted` flag to true.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function deletePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): void;

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
}
