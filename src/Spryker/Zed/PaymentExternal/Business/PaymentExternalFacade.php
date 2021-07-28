<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Business;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalBusinessFactory getFactory()
 * @method \Spryker\Zed\PaymentExternal\Persistence\PaymentExternalEntityManagerInterface getEntityManager()
 */
class PaymentExternalFacade extends AbstractFacade implements PaymentExternalFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function addPaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodTransfer
    {
        return $this->getFactory()
            ->createPaymentExternalCreator()
            ->addPaymentMethod($paymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function deletePaymentMethod(PaymentMethodTransfer $paymentMethodTransfer): void
    {
        $this->getFactory()
            ->createPaymentExternalDeleter()
            ->deletePaymentMethod($paymentMethodTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer): PaymentMethodsTransfer
    {
        return $this->getFactory()
            ->createPaymentMethodsFilter()
            ->filterPaymentMethods($paymentMethodsTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildNotDeletedPaymentMethodTableQueryCriteria(): QueryCriteriaTransfer
    {
        return $this->getFactory()
            ->createPaymentExternalMethodQueryExpander()
            ->buildNotDeletedPaymentMethodTableQueryCriteria();
    }
}
