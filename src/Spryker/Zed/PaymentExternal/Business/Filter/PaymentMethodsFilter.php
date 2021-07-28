<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;

class PaymentMethodsFilter implements PaymentMethodsFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer): PaymentMethodsTransfer
    {
        $filteredPaymentMethodTransfers = new ArrayObject();

        foreach ($paymentMethodsTransfer->getMethods() as $paymentMethod) {
            if ($paymentMethod->getIsDeleted() !== true) {
                $filteredPaymentMethodTransfers->append($paymentMethod);
            }
        }

        $paymentMethodsTransfer->setMethods($filteredPaymentMethodTransfers);

        return $paymentMethodsTransfer;
    }
}
