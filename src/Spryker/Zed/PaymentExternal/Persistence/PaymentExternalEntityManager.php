<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Persistence;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalPersistenceFactory getFactory()
 */
class PaymentExternalEntityManager extends AbstractEntityManager implements PaymentExternalEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function deletePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): void
    {
        $paymentMethodEntity = $this->getFactory()
            ->getPaymentMethodQuery()
            ->filterByPaymentMethodKey($paymentMethodTransfer->getPaymentMethodKey())
            ->findOne();

        if ($paymentMethodEntity === null) {
            return;
        }

        $paymentMethodEntity->setIsDeleted(true);
        $paymentMethodEntity->save();

        $paymentMethodTransfer->setIsDeleted($paymentMethodEntity->getIsDeleted());
    }
}
