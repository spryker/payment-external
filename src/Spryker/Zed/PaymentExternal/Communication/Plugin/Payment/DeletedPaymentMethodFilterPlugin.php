<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentExternal\Communication\Plugin\Payment;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PaymentExtension\Dependency\Plugin\PaymentMethodFilterPluginInterface;

/**
 * @method \Spryker\Zed\PaymentExternal\Business\PaymentExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\PaymentExternal\PaymentExternalConfig getConfig()
 * @method \Spryker\Zed\PaymentExternal\Communication\PaymentExternalCommunicationFactory getFactory()
 */
class DeletedPaymentMethodFilterPlugin extends AbstractPlugin implements PaymentMethodFilterPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer): PaymentMethodsTransfer
    {
        return $this->getFacade()
            ->filterPaymentMethods($paymentMethodsTransfer);
    }
}
