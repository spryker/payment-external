<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\PaymentExternal\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;

class PaymentExternalDataHelper extends Module
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    public function findPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): ?PaymentMethodTransfer
    {
        $paymentMethodEntity = SpyPaymentMethodQuery::create()
            ->filterByArray($paymentMethodTransfer->modifiedToArrayNotRecursiveCamelCased())
            ->findOne();

        if (!$paymentMethodEntity) {
            return null;
        }

        return $paymentMethodTransfer->fromArray($paymentMethodEntity->toArray(), true);
    }
}
